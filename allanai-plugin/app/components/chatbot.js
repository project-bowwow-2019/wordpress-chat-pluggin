import React from 'react';
import ChatWidget from './chatWidget';
import PropTypes from 'prop-types';

class Chatbot extends React.Component {
  constructor(props){
    super(props);
  }

  render(){
    return(
      <div>
        <ChatWidget/>
      </div>
    )
  }
}
export default Chatbot;


Chatbot.propTypes = {
  wpObject: PropTypes.object
};
