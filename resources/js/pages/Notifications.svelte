<script>
    import Text from "../components/Text.svelte";
    import Notification from "../components/Notification.svelte";
    import Offer from '../components/Offer.svelte';
    import Rating from '../components/Rating.svelte';

    let notifications = [];

    fetch('/api/notifications')
        .then(res => res.json())
        .then(data => {
            notifications = data['data'];
        });

</script>

<div class="text-2xl font-bold">
    <Text text="notifications-title" />
</div>

{#each notifications as notification}
    <div class="w-full p-2">
        {#if notification.type == 0} <!-- wishlist --> 
            <Notification bind:notification={notification} title="wishlist" arg={notification.offer.name} created_at={notification.created_at}>
                <Offer offer={notification.offer} />
            </Notification>
        {:else if notification.type == 1} <!-- rating -->
            <Notification bind:notification={notification} title="rating" arg={notification.offer.author_email} created_at={notification.created_at}>
                <Rating offer={notification.offer} />
            </Notification>
        {:else if notification.type == 2} <!-- active reservation --> 
            <Notification bind:notification={notification} title="active-reservation" arg={notification.offer.name} created_at={notification.created_at}>
            </Notification>
        {:else if notification.type == 3}
            <Notification bind:notification={notification} title="new-reservation" arg={notification.offer.name} created_at={notification.created_at}>
                <MyOffer offer={notification.offer} editing={null} openned={null} />
            </Notification>
        {:else}
            <Text text="notifications-unsupported" />
        {/if}
    </div>
    <hr class="my-2 border-t-2 rounded-sm border-primary">
{/each}
