import React from 'react';
import 'react-chat-widget/lib/styles.css';
import {Widget, addResponseMessage} from 'react-chat-widget';

class ChatWidget extends React.Component {
  constructor(props){
    super(props);
    this.state = {
      mounted:false,
      responseMessage: "Hello!",
    }
  }

  componentDidMount(){
    this.setState({mounted:true});
    addResponseMessage(this.state.responseMessage)
  }

  handleNewUserMessage = (newMessage) => {
    console.log(`New message incoming! ${newMessage}`);
    // Now send the message throught the backend API
    addResponseMessage('Got the message');
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
