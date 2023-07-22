<script>
    import Text from "../components/Text.svelte";
</script>

<div class="text-2xl font-bold">
    <Text text="add-title" />
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-2 xl:w-1/2 2xl:1/3">
    <div class="flex flex-col md:col-span-2">
        <label for="subject" class="text-xs text-secondary"><Text text="year" /></label>
        <select id="subject" name="subject" class="input">
            <option value=""></option>
        </select>
    </div>
    <div class="flex overflow-x-auto card md:col-span-2 gap-2">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">        
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="price" /></label>
        <input type="number" class="input">
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="book-state" /></label>
        <select id="subject" name="subject" class="input">
            <option value=""></option>
        </select>
    </div>
    <input type="file">
    <button class="button"><Text text="add-button" /></button>
</div>
