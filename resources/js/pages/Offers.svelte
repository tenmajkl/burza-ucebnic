<script>
    import Text from "../components/Text.svelte";
</script>

<div class="text-2xl font-bold">
    <Text text="offers-title" />
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-2">
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="subject" /></label>
        <select id="subject" name="subject" class="input">
            <option value=""></option>
        </select>
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="book-state" /></label>
        <select id="subject" name="subject" class="input">
            <option value=""></option>
        </select>
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="sorting" /></label>
        <select id="subject" name="subject" class="input">
            <option value=""></option>
        </select>
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="offer-state" /></label>
        <select id="subject" name="subject" class="input">
            <option value=""></option>
        </select>
    </div>
</div>

<hr class="my-2 border-t-4 rounded-sm border-secondary">

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2">
    <div class="flex card gap-5">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <div class="flex flex-col justify-between font-bold">
            <div>
                <div class="text-xl">Programovaci jazyk C</div>
                <div class="text-sm text-secondary">@kripa19111</div>
                <div class="text-sm text-secondary">2023-07-23T21:01:46</div>
            </div>
            <div>
                <div class="text-sm text-secondary">NOVA</div>
                <div class="flex gap-3">
                    <div class="text-xl">200 Kc</div>
                    <button class="text-sm button">REZERVOVAT</button>
                </div>
            </div>
        </div>
    </div>
</div>
