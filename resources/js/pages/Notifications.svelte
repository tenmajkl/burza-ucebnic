<script>
    import Text from "../components/Text.svelte";
    import Notification from "../components/Notification.svelte";
    import Offer from '../components/Offer.svelte';
    import Rating from '../components/Rating.svelte';
    import MyOffer from "../components/MyOffer.svelte";

    let notifications = null;

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

{#if notifications}
    {#each notifications as notification}
        <div class="w-full p-2">
            {#if notification.type == 0} <!-- wishlist --> 
                <Notification bind:notification={notification} title="wishlist" arg={notification.offer.name} created_at={notification.created_at} bind:data={data}>
                    <Offer offer={notification.offer} />
                </Notification>
            {:else if notification.type == 1} <!-- rating -->
                <Notification bind:notification={notification} title="rating" arg={notification.offer.user.email} created_at={notification.created_at} bind:data={data}>
                    <Rating notification={notification} />
                </Notification>
            {:else if notification.type == 2} <!-- active reservation --> 
                <Notification bind:notification={notification} title="active-reservation" arg={notification.offer.name} created_at={notification.created_at} bind:data={data}>
                </Notification>
            {:else if notification.type == 3} <!-- new reservation -->
                <Notification bind:notification={notification} title="new-reservation" arg={notification.offer.name} created_at={notification.created_at} bind:data={data}>
                    <MyOffer offer={notification.offer} editing={null} openned={null} />
                </Notification>
            {:else if notification.type == 4} <!-- editing -->
                <Notification bind:notification={notification} title="editing" arg={notification.offer.name} created_at={notification.created_at} bind:data={data}>
                    <Offer offer={notification.offer} />
                </Notification>
            {:else}
                <Text text="notifications-unsupported" />
            {/if}
        </div>
        <hr class="my-2 border-t-2 rounded-sm border-primary">
    {:else}
            <Text text="no-notifications"></Text>
    {/each}
{:else}
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-clockwise animate-spin" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
      <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
    </svg>
{/if}
