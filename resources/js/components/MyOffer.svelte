<script>
    import Conversation from "../components/Conversation.svelte";

    import Text from "../components/Text.svelte";

    export let offer;
    let result = 200; 
    let reservation;
    export let editing; 
    export let openned;
    export let index;
    let enabled = true;
    let init_price = offer.price;

    function interestedText(interested)
    {
        if (interested === 1) {
            return 'interested-1';
        }
        if (interested == 2 || interested == 3) {
            return 'interested-2-3';
        }

        return 'interested';
    }

    async function getReservation(offer)
    {
        const res = await fetch('/api/reservations/' + offer.id);
        const data = await res.json();
        return data.data;
    }

    function edit() 
    {
        if (offer.price == init_price) {
            return;
        }

        fetch('/api/offers/' + offer.id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                price: offer.price
            })
        })
            .then(response => response.json())
            .then(res => {
                result = res.status;
                init_price = price;
            });
    }

    function remove()
    {
        fetch('/api/offers/' + offer.id + '/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: ''
        })

        enabled = false;
    }
</script>

{#if enabled}
<div class="flex flex-col card gap-5 {openned === index ? 'md:col-span-2 xl:col-span-3 2xl:col-span-4' : ''}">
    <div class="flex gap-5">
        <img src="/static/img/offers/{offer.id}" alt="cecko" class="card-image">
        <div class="flex flex-col justify-between font-bold">
            <div>
                <div class="text-xl">{offer.name}</div>
                <div class="text-sm text-secondary">{offer.reservations} <Text text="{interestedText(offer.reservations)}" /></div>
            </div>
            <div>
                <div class="text-sm text-secondary"><Text text="state-{offer.state}" /></div>
                <div class="flex gap-3">
                    <div class="text-xl flex items-center">
                        <input type="number" bind:value={offer.price} class="w-20 input {result != 200 ? 'border-2 border-red rounded-0 mr-1' : ''}" required min='0' max='999' on:keydown={(e) => {if (e.key == 'Enter') { edit() }}}> <Text text="currency" />
                    </div>
                    <button on:click={async () => {reservation = await getReservation(offer); openned = openned == index ? null : index;}} disabled={offer.reservations == 0}><i class="text-lg bi bi-chat text-blue"></i></button>
                    <button on:click={() => remove()}><i class="text-lg bi bi-trash text-red"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div>
        {#if openned === index}
            <Conversation reservation={reservation} opponent={reservation.author} />
        {/if}
    </div>
</div>
{/if}
