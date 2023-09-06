<script>
    import Text from './Text.svelte';
    import {_text} from '../main.js';

    export let offer;

    function reserve(offer)
    {
        fetch(`/api/reservations/make/${offer}`, {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: '{}'
        })
            .then(res => res.json())
            .then(data => {
                console.log(data);
            })
        ;
    }
</script>

<div class="flex gap-5">
    <img src="/static/img/offers/{offer.id}" alt="cecko" class="card-image">
    <div class="flex flex-col justify-between font-bold">
        <div>
            <div class="text-xl">{offer.name}</div>
            <div class="text-sm text-secondary">@{offer.author_email} (<span class="text-{offer.author_rating > 0 ? 'blue' : 'red'}" title={_text('rating', offer.author_email)}>{offer.author_rating}</span>)</div>
            <div class="text-sm text-secondary">{offer.created_at}</div>
        </div>
        <div>
            <div class="text-sm text-secondary"><Text text='state-{offer.state}' /></div>
            <div class="flex gap-3">
                <div class="text-xl">{offer.price} <Text text='currency' /></div>
                <button class="text-sm button" on:click={() => reserve(offer.id)}><Text text='reserve' /></button>
            </div>
        </div>
    </div>
</div>
