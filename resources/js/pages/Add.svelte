<script>
    import Text from "../components/Text.svelte";
    import imageCompression from 'browser-image-compression';

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

    let result = null;

    export let selected = 0;

    let loading = 0;

    async function send() {
        loading = 1;
        const image = document.querySelector('input[type=file]').files[0];
        const options = {
            maxSizeMB: 0.1,
            maxWidthOrHeight: 1920,
            useWebWorker: true,
        }

        try {
            const compressed = await imageCompression(image, options);
            offer.image = await imageCompression.getDataUrlFromFile(compressed);
        } catch (error) {
            console.log(error);
            return;
        }

        fetch('/api/offers/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(offer)
        })
        .then(res => res.json())
        .then(data => {
            result = data;
            if (result && result.status == '200') {
                offer = { state: 0 };
                selected = 0;
            }
        });
        loading = 0;
    }
</script>

<div class="text-2xl font-bold">
    <Text text="add-title" />
</div>
{#if result}
    <div class="alert">
        {result.message}
    </div>
{/if}
<div class="grid grid-cols-1 md:grid-cols-2 gap-2 xl:w-1/2 2xl:w-1/3">
    <div class="flex flex-col md:col-span-2">
        <label for="subject" class="text-xs text-secondary"><Text text="year" /></label>
            <select id="year" name="year" class="input" bind:value={year} required>
                {#each years as year, index}
                    <option value="{index}">{year.name}</option>
                {/each}
            </select>
    </div>
    <div class="text-xs text-secondary"><Text text="book" /></div>
    <div class="flex overflow-x-auto md:col-span-2 gap-3 pb-2">
        {#if years[year]}
            {#each years[year].books as book}
                <div class="w-1/2 md:w-1/4 font-bold card">
                    <div ><img src="/img/cover/{book.id}.png" alt="Photo of {book.name}" class="object-cover w-full h-full rounded-md"></div>
                    <div>
                        <div class="text-xl">{book.name}</div>
                        <div class="text-xs text-secondary">{book.author}</div>
                        <div class="text-xs text-secondary">{book.publisher} {book.release_year}</div>
                    </div>
                    <button class="{offer.book == book.id ? 'button' : 'button-secondary'} " on:click={() => offer.book = book.id}><Text text="{offer.book === book.id ? 'selected' : 'select'}" /></button>
                    {#if book.average_price > 0} 
                        <div><Text text="average-price" />: {book.average_price} <Text text="currency" /></div>
                    {/if}
                    {#if book.max_price > 0} 
                        <div><Text text="average-max-price" />: {book.average_max_price} <Text text="currency" /></div>
                    {/if}
                </div>
            {/each}
        {/if}
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="price" /></label>
        <input type="number" class="input" bind:value={offer.price} required min='1' max='999'>
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="book-state" /></label>
        <select id="subject" name="subject" class="input" bind:value={offer.state} required>
            {#each states as state, id}
                <option value="{id + 1}"><Text text="state-{state}" /></option>
            {/each}
        </select>
    </div>
<div class="flex flex-col">
    <div class="text-xs text-secondary"><Text text="photo" /></div>
    <input type="file" class="input" required></div>
</div>
{#if loading}
    <button class="button mt-2">
        <div class="animate-spin">
            <i class="bi bi-arrow-clockwise"></i>
        </div>
    </button>
{:else}
    <button class="button mt-2" on:click={send}><Text text="add-button" /></button>
{/if}
