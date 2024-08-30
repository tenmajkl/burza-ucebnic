<script>
    // TODO selector
    import Text from "../components/Text.svelte";

    let wishlist = [];
    let available = null;

    let error = null;

    export let selected;

    fetch("/api/wishlist")
        .then(response => response.json())
        .then(data => {
            wishlist = data['data'][0];
            available = data['data'][1];
        });


    let banner = false;

    function create(book)
    {
        fetch('/api/wishlist/'+available[book].id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ // make this work
                max_price: available[book].price
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.code === 400) {
                    error = book;
                    return;
                }
                wishlist = data['data'][0];
                available = data['data'][1];
                error = null;
            });

        banner = true;

    }

    function remove(inquiry)
    {
        fetch('/api/wishlist/'+inquiry+'/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: '{}'
        })
            .then(res => res.json())
            .then(data => {
                wishlist = data['data'][0];
                available = data['data'][1];
            })
    }

    function search(inquiry) 
    {
        fetch("/api/wishlist/search/" + inquiry.id)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                window.history.pushState('page2', 'Title', '?less_than=' + inquiry.max_price + '&subject=' + data.data);
                selected = 0;
            });       
    }


</script>

<div class="text-2xl font-bold">
    <Text text="wishlist-title" />
</div>

{#if banner}
    <div class="alert-success mb-3 flex justify-between">
        <Text text="wishlist-added"></Text>
            <i class="bi bi-check text-xl" on:click={() => banner = false}></i>
    </div>
{/if}

{#if available === null} 
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-clockwise animate-spin" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
      <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
    </svg>
{:else if available.length != 0}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2">
        {#each wishlist as inquiry}
            <div class="flex card gap-5">
                <!--<img src="/img/cover/{inquiry.book.id}.png" alt="cecko" class="card-image">-->
                <div class="flex flex-col justify-between font-bold">
                    <div>
                        <div class="text-xl">{inquiry.book.name}</div>
                        <div class="text-sm text-secondary">{inquiry.book.author}</div>
                        <div class="text-sm text-secondary">{inquiry.book.release_year}</div>
                        <div class="text-sm"><Text text="wishlist-max-price" /> {inquiry.max_price} <Text text="currency"/></div>                  
                    </div>
                    <div class="flex gap-3">
                        <button class="text-sm uppercase button" on:click={() => search(inquiry)}><Text text="search" /></button>
                        <button class="text-sm uppercase button-red" on:click={() => remove(inquiry.id)}><Text text="remove" /></button>
                    </div>
                </div>
            </div>
        {:else}
            <div class="text-xl"><Text text="wishlist-empty" /></div>
        {/each}
    </div>

    <hr class="my-2 border-t-4 rounded-sm border-secondary">

    <div class="text-2xl font-bold">
        <Text text="wishlist-select-title" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2">
        {#each available as book, index}
            {#if wishlist.filter((item) => item.book.id === book.id).length === 0}
                <div class="flex card gap-5">
                    <!--<img src="/img/cover/{book.id}.png" alt="{book.name}" class="card-image">-->
                    <div class="flex flex-col justify-between font-bold">
                        <div>
                            <div class="text-xl">{book.name}</div>
                            <div class="text-sm text-secondary">{book.author}</div>
                            <div class="text-sm text-secondary">{book.release_year}</div>
                        </div>
                        <div class="flex gap-3 items-center">
                            <Text text="wishlist-max-price" />:
                            <input type="number" class="w-1/3 input {error === index ? 'border-2 border-red' : ''}" placeholder="..." bind:value={book.price} max='999' min='1' required>
                            <Text text="currency" />
                            <button class="text-sm uppercase button" on:click={() => create(index)}><Text text="add" /></button>
                        </div>
                    </div>
                </div>
            {/if}
        {/each}
    </div>
{:else}
    <Text text="no-wishlist"></Text>
{/if}
