<script>
    import Text from "../components/Text.svelte";
</script>

<div class="text-2xl font-bold">
    <Text text="add-title" />
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-2 xl:w-1/2 2xl:w-1/3">
    <div class="flex flex-col md:col-span-2">
        <label for="subject" class="text-xs text-secondary"><Text text="year" /></label>
        <select id="subject" name="subject" class="input">
            <option value=""></option>
        </select>
    </div>
    <div class="flex overflow-x-auto md:col-span-2 gap-3">
        <div class="flex flex-col w-1/3 font-bold card">
            <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecky" class="object-cover w-full rounded-md h-1/2">
            <div class="text-xl">Programovaci jazyk C</div>
            <div class="text-xs text-secondary">Ritchie</div>
            <div class="text-xs text-secondary">2017</div>
        </div>
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
    <input type="file" class="input">
    <button class="button"><Text text="add-button" /></button>
</div>
