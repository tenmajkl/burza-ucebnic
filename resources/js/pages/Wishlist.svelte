<script>
    // TODO selector
    import Text from "../components/Text.svelte";

    let wishlist = [];
    let available = [];

    fetch("/api/wishlist")
        .then(response => response.json())
        .then(data => {
            wishlist = data['data'][0];
            available = data['data'][1];
        });

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
                    // TODO error
                    return;
                }
                wishlist = data['data'][0];
                available = data['data'][1];
            });
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
</script>

<div class="text-2xl font-bold">
    <Text text="wishlist-title" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2">
    {#each wishlist as inquiry}
        <div class="flex card gap-5">
            <img src="/img/cover/{inquiry.book.id}.png" alt="cecko" class="card-image">
            <div class="flex flex-col justify-between font-bold">
                <div>
                    <div class="text-xl">{inquiry.book.name}</div>
                    <div class="text-sm text-secondary">{inquiry.book.author}</div>
                    <div class="text-sm text-secondary">{inquiry.book.release_year}</div>
                    <div class="text-sm"><Text text="wishlist-max-price" /> {inquiry.max_price} <Text text="currency"/></div>                  
                </div>
                <div class="flex gap-3">
                    <button class="text-sm uppercase button"><Text text="search" /></button>
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
                <img src="/img/cover/{book.id}.png" alt="{book.name}" class="card-image">
                <div class="flex flex-col justify-between font-bold">
                    <div>
                        <div class="text-xl">{book.name}</div>
                        <div class="text-sm text-secondary">{book.author}</div>
                        <div class="text-sm text-secondary">{book.release_year}</div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <Text text="wishlist-max-price" />:
                        <div class="flex gap-2">
                            <div class="flex items-center gap-1 w-1/2">
                                <input type="number" class="w-full input" placeholder="..." bind:value={book.price}>
                                <Text text="currency" />
                            </div>
                            <button class="text-sm uppercase button" on:click={() => create(index)}><Text text="add" /></button>
                        </div>
                    </div>
                </div>
            </div>
        {/if}
    {/each}
</div>
