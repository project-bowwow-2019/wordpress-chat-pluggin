import React, { Component } from 'react';
import PropTypes from 'prop-types';
import ContactForm from '../components/contactForm'
import Chatbot from '../components/chatbot'

export default class Widget extends Component {
  render() {
    return (
      <div>
        <section className='widget'>
          <h1 className = 'widget-title'> {this.props.wpObject.title}</h1>
          <ContactForm wpObject={this.props.wpObject} />
          <Chatbot wpObject ={this.props.wpObject}/>
        </section>
      </div>
    );
  }
}

Widget.propTypes = {
  wpObject: PropTypes.object
};
