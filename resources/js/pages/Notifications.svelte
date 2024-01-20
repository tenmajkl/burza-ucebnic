<script>
    import Text from "../components/Text.svelte";
    import Notification from "../components/Notification.svelte";
    import Offer from '../components/Offer.svelte';
    import Rating from '../components/Rating.svelte';

    let notifications = [];

    export let data;

    fetch('/api/notifications')
        .then(res => res.json())
        .then(data => {
            notifications = data['data'];
        });

    function readAll() {
        fetch('/api/notifications/read-all', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: '{}'
        }).then((res) => res.json())
        .then((res) => {
            notifications = res['data']
        })
    }

    function clear() {
        fetch('/api/notifications/clear', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: '{}'
        }).then((res) => res.json())
        .then((res) => {
            notifications = res['data']
        })
    }

</script>

<div class="flex justify-between w-full">
    <div class="text-2xl font-bold">
        <Text text="notifications-title" />
    </div>

    <div class="flex gap-2">
        <i class="bi bi-eye text-2xl" on:click={readAll}></i>
        <i class="bi bi-trash text-2xl" on:click={clear}></i>
    </div>
</div>
{#each notifications as notification}
    <div class="w-full p-2">
        {#if notification.type == 0} <!-- wishlist --> 
            <Notification bind:notification={notification} title="wishlist" arg={notification.offer.name} created_at={notification.created_at} bind:data={data}>
                <Offer offer={notification.offer} />
            </Notification>
        {:else if notification.type == 1} <!-- rating -->
            <Notification bind:notification={notification} title="rating" arg={notification.offer.reservation.author_email} created_at={notification.created_at} bind:data={data}>
                <Rating notification={notification} />
            </Notification>
        {:else if notification.type == 2} <!-- active reservation --> 
            <Notification bind:notification={notification} title="active-reservation" arg={notification.offer.name} created_at={notification.created_at} bind:data={data}>
            </Notification>
        {:else if notification.type == 3} <!-- new reservation -->
            <Notification bind:notification={notification} title="new-reservation" arg={notification.offer.name} created_at={notification.created_at} bind:data={data}>
                <MyOffer offer={notification.offer} editing={null} openned={null} />
            </Notification>
        {:else}
            <Text text="notifications-unsupported" />
        {/if}
    </div>
    <hr class="my-2 border-t-2 rounded-sm border-primary">
{/each}
