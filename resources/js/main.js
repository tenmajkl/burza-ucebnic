import App from './App.svelte';

export function _text(text, arg) {
    return arg 
        ? translations[text].replace('%arg', arg)
        : translations[text]
    ;
}

export default new App({
    target: document.getElementById('app'),
});
