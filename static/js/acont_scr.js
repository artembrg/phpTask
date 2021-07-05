// Display auth form
function AuthForm() {
    let info = document.getElementById('form_info_text');
    info.innerText = 'Авторизация';
    let email = document.getElementById('email');
    email.setAttribute('type', 'hidden');
    let passw_conf = document.getElementById('password_conf');
    passw_conf.setAttribute('type', 'hidden');
    let submit = document.getElementById('submit');
    submit.setAttribute('name', 'auth');
    submit.innerText = 'Авторизация';
    let header_content = document.getElementById('header_content');
    header_content.innerText = 'Регистрация';
    header_content.setAttribute('href', 'javascript:RegForm()');
    let errors = document.getElementById('main_content_errors_list');
    errors.innerText = '';
}

// Display reg form
function RegForm() {
    let info = document.getElementById('form_info_text');
    info.innerText = 'Регистрация';
    let email = document.getElementById('email');
    email.setAttribute('type', 'text');
    let passw_conf = document.getElementById('password_conf');
    passw_conf.setAttribute('type', 'password');
    let submit = document.getElementById('submit');
    submit.setAttribute('name', 'reg');
    submit.innerText = 'Регистрация';
    let header_content = document.getElementById('header_content');
    header_content.innerText = 'Авторизация';
    header_content.setAttribute('href', 'javascript:AuthForm()');
    let errors = document.getElementById('main_content_errors_list');
    errors.innerText = '';
}