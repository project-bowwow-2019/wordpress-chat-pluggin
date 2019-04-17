import React from 'react';
import ChatWidget from './chatWidget';
import PropTypes from 'prop-types';
import fetchWP from '../utils/fetchWP';
import uuidv4 from 'uuid/v4';

const baseURL = 'https://allanai-chatbot-backend.appspot.com';
//https://allanai-chatbot-backend.appspot.com
//http://localhost:5000

class Chatbot extends React.Component {
  constructor(props){
    super(props);
    this.state = {
      sessionID:'',
      userID:'',
      currentContext:[],
      handledContext:[],
      handledContextNew:[],
      userInfo:{
        prevTest:{
          relativeLocation:'',
          date:'',
          dateRange:'',
          passFail:'',
        },
        appointment:{
          time:'',
          date:'',
          dateRange:'',
        },
        car:{
          type:'',
          model:'',
          year:'',
          utterance:'',
        },
        name:'',
        phone:'',
        email:'',
      },
      apiStatus:'',
      chatbotMessage:'',
    };
    this.fetchWP = new fetchWP({
      restURL: this.props.wpObject.api_url,
      restNonce: this.props.wpObject.api_nonce,
    });
    this.getChatbotResponse = this.getChatbotResponse.bind(this);
  }

  async componentDidMount () {
    if (this.state.sessionID === ""){
      var id = await uuidv4();
      await this.setState({sessionID:id})
      localStorage.setItem("sessionID", id)
    }
    this.getAgentID();
    this.checkApi()
    .then(res => this.setState({ apiStatus: res.status }))
    .catch(err => console.log(err));
  }

  getAgentID = () => {
    this.fetchWP.get( 'admin' )
    .then(
      (json) => this.setState({
        userID: json.value.agentID,
      }),
      (err) => console.log( 'error', err )
    );
  };

  async checkApi () {
    const response = await fetch(baseURL+'/livecheck');
    const body = await response.json();
    if (response.status !== 200) throw Error(body.message);
    return body;
  }

  getChatbotResponse(message){
    return new Promise(async (resolve,reject) =>{
      const postObject = {
        userID:this.state.userID,
        sessionID:this.state.sessionID,
        userUtterance:message,
        currentContext:this.state.currentContext,
        handledContextNew:this.state.handledContextNew,
        hanldedContext:this.state.handledContext,
        userInfo:this.state.userInfo,
      }
      fetch(baseURL+'/chatbotTest/api', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(postObject)
      })
      .then(res => res.json())
      .then(async data => {
        await this.setState({chatbotMessage:data.fulfillmentText})
        if(data.contexts!=null){
          this.setState({currentContext:data.contexts});
        }
        if(data.handledContextNew !=null){
          this.setState({handledContextNew:data.handledContextNew});
        }
        if(data.handledContextNew != null){
          this.state.handledContext.push(data.handledContextNew)
        }
        if(data.prevTest != undefined){
          let newUserInfo = this.state.userInfo;
          newUserInfo.prevTest = data.prevTest;
          this.setState({userInfo:newUserInfo})
        }
        if(data.appointment != undefined){
          let newUserInfo = this.state.userInfo;
          newUserInfo.appointment = data.appointment;
          this.setState({userInfo:newUserInfo})
        }
        if(data.car != undefined){
          let newUserInfo = this.state.userInfo;
          newUserInfo.car = data.car;
          this.setState({userInfo:newUserInfo})
        }
        resolve();
      })
      .catch(err => {
        console.log(err.status);
        reject();
      })
    });
  }

  render(){
    return(
      <div>
        <ChatWidget getChatbotResponse={this.getChatbotResponse} chatbotMessage={this.state.chatbotMessage}/>
      </div>
    )
  }
}
export default Chatbot;


Chatbot.propTypes = {
  wpObject: PropTypes.object
};
