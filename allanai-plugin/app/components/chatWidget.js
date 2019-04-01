import React from 'react';
import 'react-chat-widget/lib/styles.css';
import {Widget, addResponseMessage} from 'react-chat-widget';

class ChatWidget extends React.Component {
  constructor(props){
    super(props);
    this.state = {
      mounted:false,
      responseMessage: "Hello! How may I help?",
    }
  }

  componentDidMount(){
    this.setState({mounted:true});
    addResponseMessage(this.state.responseMessage)
  }

  handleNewUserMessage = async (newMessage) => {
    console.log(`New message incoming! ${newMessage}`);
    // Now send the message throught the backend API
    await this.props.getChatbotResponse(newMessage)
    await this.setState({responseMessage:this.props.chatbotMessage})
    addResponseMessage(this.state.responseMessage);
  }

  render(){
    return(
      <div className='App'>
        <Widget
          subtitle='Smogshop agent test'
          title = 'Chatbot test'
          handleNewUserMessage={this.handleNewUserMessage}/>
      </div>
    )
  }
}

export default ChatWidget;
