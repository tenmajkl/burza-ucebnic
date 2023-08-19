<script>
    import Text from "../components/Text.svelte";
    let states = [];
    let years = [];

    fetch('/api/offers/create')
        .then(response => response.json())
        .then(data => {
            states = data.states;
                years = data.years;
            });

    let offer = { state: 0 };
    let year = 0;

    let result = {};

    export let selected = 0;

    function send() {
        let reader = new FileReader();
        try {
            reader.readAsDataURL(document.querySelector('input[type=file]').files[0]);
        } catch (_) {
                result.message = translations['missing-image'];
            return;
        }
        reader.onload = function () {
            offer.image = reader.result;
        };
        reader.onerror = function (error) {
            result.message = translations['broken-image'];
        };
        fetch('/api/offers/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(offer)
        })
        .then(data => {
            result = data;
        });

        if (result.code && result.code === 200) {
            offer = { state: 0 };
            selected = 0;
        }
    }
</script>

<div class="text-2xl font-bold">
    <Text text="add-title" />
</div>
{#if result.message && result.message != "ok"}
    <div class="alert">
        {result.message}
    </div>
{/if}
<div class="grid grid-cols-1 md:grid-cols-2 gap-2 xl:w-1/2 2xl:w-1/3">
    <div class="flex flex-col md:col-span-2">
        <label for="subject" class="text-xs text-secondary"><Text text="year" /></label>
            <select id="year" name="year" class="input" bind:value={year} >
                {#each years as year, index}
                    <option value="{index}">{year.name}</option>
                {/each}
            </select>
    </div>
    <div class="flex overflow-x-auto md:col-span-2 gap-3">
        {#if years[year]}
            {#each years[year].books as book}
                <div class="w-1/4 font-bold card">
                    <div class="h-1/2"><img src="/img/cover/{book.id}.png" alt="Photo of {book.name}" class="object-cover w-full h-full rounded-md"></div>
                    <div>
                        <div class="text-xl">{book.name}</div>
                        <div class="text-xs text-secondary">{book.author}</div>
                        <div class="text-xs text-secondary">{book.publisher} {book.release_year}</div>
                    </div>
                    <button class="{offer.book == book.id ? 'button' : 'button-secondary'} " on:click={() => offer.book = book.id}><Text text="{offer.book === book.id ? 'selected' : 'select'}" /></button>
                </div>
            {/each}
        {/if}
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="price" /></label>
        <input type="number" class="input" bind:value={offer.price}>
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="book-state" /></label>
        <select id="subject" name="subject" class="input" bind:value={offer.state}>
            {#each states as state, id}
                <option value="{id}"><Text text="state-{state}" /></option>
            {/each}
        </select>
    </div>
    <input type="file" class="input">
    <button class="button" on:click={send} ><Text text="add-button" /></button>
</div>
