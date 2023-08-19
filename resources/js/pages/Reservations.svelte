<script>
    import Conversation from "../components/Conversation.svelte";
    import Text from "../components/Text.svelte";
    import QR from "../components/QR.svelte";

    let message = null;
    let qr = null;

    let data = [];

    fetch("/api/reservations")
        .then(response => response.json())
        .then(res => {
            data = res.data;
        })
        .catch(error => {
            console.error(error);
        });

</script>

<div class="text-2xl font-bold">
    <Text text="reservations-title" />
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-2">
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="subject" /></label>
        <select id="subject" name="subject" class="input">
            <option value=""></option>
        </select>
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="offer-state" /></label>
        <select id="subject" name="subject" class="input">
            <option value=""></option>
        </select>
    </div>
</div>

<hr class="my-2 border-t-4 rounded-sm border-secondary">

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2">
    {#each data as reservation, index}
         <div class="flex flex-col card gap-5 {message === index ? 'md:col-span-2 xl:col-span-3 2xl:col-span-4' : ''}">
            <div class="flex gap-5">
                <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
                <div class="flex flex-col justify-between font-bold">
                    <div>
                        <div class="text-xl">{reservation.offer.name}</div>
                        <div class="text-sm text-secondary">@{reservation.offer.author_email}</div>
                    </div>
                    <div>
                        <div class="text-sm text-secondary"><Text text="state-{reservation.offer.state}" /></div>
                        <div class="flex gap-3">
                            <div class="text-xl">{reservation.offer.price} Kc</div>
                            <i class="text-lg bi bi-chat text-blue" on:click={() => message = message === index ? null : (qr = null) || index}></i>
                            {#if reservation.active}
                                <i class="text-lg bi bi-qr-code text-blue" on:click={() => qr = qr === index ? null : (message = null) || index}></i>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
            {#if message === index}
                <Conversation opponent={reservation.seller} reservation={reservation} />
            {/if}
            {#if qr === index} 
                <QR reservation={reservation} />
            {/if}
         </div>           
    {/each}
</div>
