let memberState = 'register';
const registerBtn = document.querySelector('.register-btn');
// validation
function formCheck() {
  document.querySelector(`.handle-${memberState}-btn`).addEventListener('click', (e) => {
    let hasBlank = false;
    document.querySelectorAll(`.comment__${memberState} input`).forEach((el) => {
      if (!el.value) {
        hasBlank = true;
      }
    });
    if (hasBlank) {
      alert('data missing');
      e.preventDefault();
      e.stopImmediatePropagation();
    }
  });
}

if (registerBtn) {
  document.querySelector('.register-btn').addEventListener('click', () => {
    document.querySelector('.comment__register').classList.remove('hidden');
    document.querySelector('.dark_bgc').classList.remove('hidden');
    formCheck();
  });
  document.querySelector('.login-btn').addEventListener('click', () => {
    document.querySelector('.comment__login').classList.remove('hidden');
    document.querySelector('.dark_bgc').classList.remove('hidden');
    memberState = 'login';
    formCheck();
  });
}

document.querySelectorAll('.cancel').forEach((el) => {
  el.addEventListener('click', () => {
    el.parentNode.classList.add('hidden');
    document.querySelector('.dark_bgc').classList.add('hidden');
  });
});

document.querySelector('.to-login').addEventListener('click', () => {
  document.querySelector('.comment__register').classList.add('hidden');
  document.querySelector('.comment__login').classList.remove('hidden');
  document.querySelector('.dark_bgc').classList.remove('hidden');
  memberState = 'login';
  formCheck();
});

document.querySelector('.to-register').addEventListener('click', () => {
  document.querySelector('.comment__login').classList.add('hidden');
  document.querySelector('.comment__register').classList.remove('hidden');
  document.querySelector('.dark_bgc').classList.remove('hidden');
  memberState = 'register';
  formCheck();
});
