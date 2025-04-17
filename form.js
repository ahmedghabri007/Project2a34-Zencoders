document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('profileForm');
  
    form.addEventListener('submit', function (e) {
      const fullname = document.getElementById('fullname').value.trim();
      const age = document.getElementById('age').value.trim();
      const gender = document.getElementById('gender').value;
  
      let errors = [];
  
      if (fullname === '') {
        errors.push('Full Name is required.');
      }
  
      if (age === '' || isNaN(age) || Number(age) < 18) {
        errors.push('Valid age (18 or older) is required.');
      }
  
      if (gender === '') {
        errors.push('Gender must be selected.');
      }
  
      if (errors.length > 0) {
        e.preventDefault(); // stop form from submitting
        alert(errors.join('\n'));
      }
    });
  });
  