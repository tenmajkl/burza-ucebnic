<script>
    export let text;
    export let arg = null;
    import {_text} from '../main.js';
</script>

{@html _text(text, arg)}
