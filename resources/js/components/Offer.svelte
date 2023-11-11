<script>
    import Text from './Text.svelte';
    import {_text} from '../main.js';

    export let offer;
    export let with_reserved = false;

    let enabled = true;

    function reserve(id)
    {
        fetch(`/api/reservations/make/${id}`, {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: '{}'
        })
            .then(res => res.json())
            .then(data => {
            })
        ;
        enabled = false;
        offer.can_be_reserved = false;
    }
</script>
{#if enabled || with_reserved == 1}
    <div class="card">
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
                            <button class="text-sm {offer.can_be_reserved ? 'button' : 'button-secondary'}" disabled={offer.can_be_reserved ? '' : '1'} on:click={() => reserve(offer.id)}><Text text='reserve' /></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}
