<script>
    import Text from './Text.svelte';
    import {_text} from '../main.js';
    import PopUp from './PopUp.svelte';

    export let offer;
    export let with_reserved = false;

    let enabled = true;

    let report = false;

    function reserve(id)
    {
        fetch(`/api/reservations/make/${id}`, {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: '{}',
        })
            .then(res => res.json())
            .then(_ => {})
        ;
        enabled = false;
        offer.can_be_reserved = false;

    }

    let reason;

    function sendReport()
    {
        fetch(`/api/offers/${offer.id}/report`, {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
                body: `{"reason":"${reason}"}`,
        }).then(res => res.json())
            .then(data => {
                report = false;
            })
    }

    let photo = false;
</script>

{#if photo}
    <PopUp bind:state={photo}>
        <img src="/static/img/offers/{offer.id}" alt="{_text('photo-book') + offer.name}" class="shadow-2xl !opacity-100 sm:w-1/2 w-3/4">
    </PopUp>
{/if}

{#if report}
    <PopUp bind:state={report}>
        <div class="card w-9/12 sm:w-1/3">
            <div class="flex justify-between">
                <Text text="report-title"></Text> @{offer.author_email}
                <i class="bi bi-x text-red text-2xl cursor-pointer" on:click={() => report = false}></i>
            </div>
            <textarea id="" name="" class="input w-full h-1/2 resize-none" placeholder="{_text('reason')}" bind:value={reason}></textarea>
            <button class="button-red" on:click={sendReport}><Text text="report"></Text></button>
        </div>            
    </PopUp>
{/if}

{#if enabled || with_reserved == 1}
    <div class="card">
        <div class="flex gap-5">
            <img src="/static/img/offers/{offer.id}" alt="{_text('photo-book') + offer.name}" class="card-image" on:click="{() => photo = true}">
            <div class="flex flex-col justify-between font-bold">
                <div>
                    <div class="text-xl">{offer.name}</div>
                    <div class="text-sm text-secondary">@{offer.author_email} (<span class="text-{offer.author_rating > 0 ? 'blue' : 'red'}" title={_text('rating', offer.author_email)}>{offer.author_rating}</span>) {offer.created_at}</div>
                </div>
                <div>
                    <div class="text-sm text-secondary"><Text text='state-{offer.state}' /></div>    
                    <div class="flex justify-center xl:items-center xl:justify-start xl:flex-row flex-col xl:gap-3">
                        <div class="text-xl">{offer.price} <Text text='currency' /></div>
<div class="flex gap-3">
                        <button class="text-sm {offer.can_be_reserved ? 'button' : 'button-secondary'}" disabled={offer.can_be_reserved ? '' : '1'} on:click={() => reserve(offer.id)}>
                            <Text text="reserve"/>
                            {#if offer.reservations != 0}
                                ({offer.reservations} <Text text="reservations-{offer.reservations < 5 ? '1' : '5' }" />)
                            {/if}
                        </button>
                        <i class="bi bi-person-fill-slash text-red text-xl" on:click={() => report = true}></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}
