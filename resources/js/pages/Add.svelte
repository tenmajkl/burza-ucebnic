<script>
    import Text from "../components/Text.svelte";
    import imageCompression from 'browser-image-compression';
    import { _text } from "../main";
    import { fade, fly, slide } from "svelte/transition";

    let states = {};
    let years = [];

    fetch('/api/offers/create')
        .then(response => response.json())
        .then(data => {
            states = data.states;
                years = data.years;
            });

    let offer = { state: "0" };
    let year = 0;

    let result = null;

    export let selected = 0;

    let loading = 0;

    async function send() {
        offer.book = offer.book.id;
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
            result = {message: _text('image-error')}
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
                selected = 4;
            } 

            if (!result) {
                result = { message:  _text('server-error') }
            }
        });
        loading = 0;
    }
</script>

<div class="text-2xl font-bold">
    <Text text="add-title" />
</div>

<div class="text-sm"><Text text="compulsory" /></div>

{#if result}
    <div class="alert">
        {result.message}
    </div>
{/if}
<div class="grid grid-cols-1 md:grid-cols-2 gap-2">
    <div class="flex flex-col md:col-span-2">
        <label for="subject" class="text-xs text-secondary"><Text text="year" /></label>
            <select id="year" name="year" class="input" bind:value={year} required>
                {#each years as year, index}
                    <option value="{index}">{year.name}</option>
                {/each}
            </select>
    </div>
    <div class="text-xs text-secondary"><Text text="book" />*</div>
    <div class="flex overflow-x-scroll md:col-span-2 gap-3 pb-4" id="books">
        {#if years[year]}
            {#each years[year].books as book}
                <div class="shrink-0 w-1/2 xl:w-1/4 font-bold card flex flex-col justify-between gap-1">
                    <!--<div><img src="/img/cover/{book.id}.png" alt="Photo of {book.name}" class="object-cover w-full h-full rounded-md"></div>!-->
                    <div class="text-xl">{book.name}</div>
                    <div class="gap-2 flex flex-col">
                        <div>
                            <div class="text-xs text-secondary">{book.author}</div>
                            <div class="text-xs text-secondary">{book.publisher} {book.release_year}</div>
                        </div>
                        <button class="{offer.book && offer.book.id == book.id ? 'button-secondary' : 'button'} " on:click={() => offer.book = book}><Text text="{offer.book && offer.book.id === book.id ? 'selected' : 'select'}" /></button>
                    </div> 
                </div>
            {/each}
        {:else}
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-clockwise animate-spin" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
              <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
            </svg>
        {/if}
    </div>

    {#if offer.book && (offer.book.average_price || offer.book.average_max_price)}
        <div class="flex gap-1 items-center md:col-span-2" transition:slide>
            {#if offer.book.average_price > 0} 
                <div class="text-xs text-secondary"><Text text="average-price"></Text>: </div>
                    {offer.book.average_price} <Text text="currency" />
            {/if}
            {#if offer.book.average_max_price > 0} 
                <div class="text-xs text-secondary"><Text text="average-max-price"></Text>: </div>

                {offer.book.average_max_price} <Text text="currency" />
            {/if}
        </div>
    {/if}
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="price" />*</label>
        <input type="number" class="input" bind:value={offer.price} required min='0' max='999'>
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="book-state" />*</label>
        <select id="subject" name="subject" class="input" bind:value={offer.state} required>
            {#each Object.entries(states) as [id, state]}
                <option value="{id}"><Text text="state-{state}" /></option>
            {/each}
        </select>
    </div>

<div class="flex flex-col">
    <div class="text-xs text-secondary"><Text text="photo" />*</div>
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
