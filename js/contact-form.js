const contactForm = document.getElementById('contact-form');

contactForm.addEventListener('submit', () => {
  event.preventDefault();

  const firstName = document.getElementById('contactFirstName').value;
  const lastName = document.getElementById('contactLastName').value;
  const email = document.getElementById('contactEmailAddress').value;
  const message = document.getElementById('contactTextArea').value;

  const body = `First Name: ${firstName}\nLast Name: ${lastName}\nEmail: ${email}\n\n${message}`;

  // Open default email app with pre-filled email message
  window.location.href = `mailto:dibgeovani@goldendata-lb.com?body=${encodeURIComponent(body)}`;
});
