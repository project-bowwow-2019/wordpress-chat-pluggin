import React from 'react';
import PropTypes from 'prop-types';
import fetchWP from '../utils/fetchWP'

export default class ContactForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      error: false,
      submitted: false,
      name: '',
      email: '',
      message: '',
    };
    this.fetchWP = new fetchWP({
      restURL: this.props.wpObject.api_url,
      restNonce: this.props.wpObject.api_nonce,
    });
  }

  handleInputChange = (event) => {
    const target = event.target;
    const value = target.value;
    const name = target.name;

    this.setState({
      [name]: value
    });
  }

  handleSubmit = (event) => {
    event.preventDefault();

    this.fetchWP.post( 'submission', {
      name: this.state.name, // send our name, email and message states to the 'submission' endpoint
      email: this.state.email,
      message: this.state.message,
    } )
    .then(
      (json) => {
        this.setState({ // if successful
          submitted: true, // set the submitted state to true
          error: false // and clear any previous error message
        }),
        console.log(`New Contact Submission: ${json.value}`) // For tutorial purposes. Shows that our data is successfully reaching and being returned from our PHP callback function.
      },
      (err) => this.setState({ // else
        error: err.message // set the returned error message as the error state
      }),
    );
  }

  render() {
    const contactForm = this.state.submitted ? <p>Thanks for getting in touch!</p> :
    <form>
      <label>
      Name:
        <input
          type="text"
          name="name"
          onChange={this.handleInputChange}
        />
      </label>

      <label>
      Email:
        <input
          type="email"
          name="email"
          onChange={this.handleInputChange}
        />
      </label>

      <label>
      Message:
        <textarea
          name="message"
          onChange={this.handleInputChange}
        />
      </label>

      <button
        id="submit"
        className="button button-primary"
        onClick = {this.handleSubmit}
      >Submit</button>

    </form>;

    const error = this.state.error ? <p className="error">{this.state.error}</p> : ''; // if there is an error, display it

    return (
      <div>
          {contactForm}
          {error}
      </div>
    );
  }
}

ContactForm.propTypes = {
  wpObject: PropTypes.object
};
