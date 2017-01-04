// Wait for the DOM to be ready
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form[name='registration']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      firstName: "required",
      lastName: "required",
phoneNumber: "required",
companyName: "required",
username: "required",
password: "required",
      emailAddress: {
        required: true,
        // Specify that email should be validated
        // by the built-in "email" rule
        email: true
      },
      
    },
    // Specify validation error messages
    messages: {
      firstName: "Please enter your first name",
      lastName: "Please enter your last name",
companyName: "Please enter your company name",
     phoneNumber: "Please enter your phone number",
      emailAddress: "Please enter a valid email address",
      username: "Please enter a username",
      password: "Please enter a password"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
});