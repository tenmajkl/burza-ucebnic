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
            <Notification bind:notification={notification} emoji='🎉' title="notification-wishlist" arg={notification.offer.name} created_at={notification.created_at}>
                <Offer offer={notification.offer} />
            </Notification>
        {:else if notification.type == 1} <!-- rating -->
            <Notification bind:notification={notification} emoji='📊' title="notification-rating" arg={notification.offer.author_email} created_at={notification.created_at}>
                <Rating offer={notification.offer} />
            </Notification>
        {:else if notification.type == 2} <!-- active reservation -->
            <Notification bind:notification={notification} emoji='✨' title="notification-active-reservation" arg={notification.offer.name} created_at={notification.created_at}>
                tbd
            </Notification>
        {:else}
            <Text text="notifications-unsupported" />
        {/if}
    </div>
    <hr class="my-2 border-t-2 rounded-sm border-primary">
{/each}
