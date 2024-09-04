<script>
    import Conversation from "../components/Conversation.svelte";
    import QR from "../components/QR.svelte";
    import Text from "../components/Text.svelte";
    import { slide } from 'svelte/transition';

    export let reservation;
    export let index;
    export let message;
    export let qr;

    let enabled = true;

    async function disable() {
        await fetch('/api/reservations/' + reservation.id + '/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        enabled = false;
    }
</script>
{#if enabled}
    <div class="flex flex-col card gap-5 {message === index ? 'md:col-span-2 xl:col-span-3 2xl:col-span-4' : ''}">
        <div class="flex gap-5">
            <img src="/static/img/offers/{reservation.offer.id}" alt="{reservation.offer.name}" class="card-image">
            <div class="flex flex-col justify-between font-bold">
                <div>
                    <div class="text-xl">{reservation.offer.name}</div>
                    <div class="text-sm text-secondary">@{reservation.offer.author_email}</div>
                </div>
                <div>
                    <div class="text-sm text-secondary"><Text text="state-{reservation.offer.state}" /></div>
                    <div class="flex gap-3">
                        <div class="text-xl">{reservation.offer.price} <Text text="currency" /></div>
                        {#if reservation.active}
                            <i class="text-lg bi bi-chat text-blue" on:click={() => message = message === index ? null : (qr = null) || index}></i>
                            <i class="text-lg bi bi-qr-code text-blue" on:click={() => qr = qr === index ? null : (message = null) || index}></i>
                            <i class="text-lg bi bi-x-lg text-red" on:click={disable}></i>
                        {:else} 
                            <i class="text-lg bi bi-clock text-blue"></i>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
        {#if message === index}
            <div transition:slide={{}}><Conversation opponent={reservation.offer.author_email} reservation={reservation} /></div>
        {/if}
        {#if qr === index} 
            <div transition:slide={{}}><QR reservation={reservation} /></div>
        {/if}
    </div>
{/if}
