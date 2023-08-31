<script>
    import Menu from './components/Menu.svelte';
    import Text from './components/Text.svelte';
    import Add from './pages/Add.svelte';
    import MyOffers from './pages/MyOffers.svelte';
    import Offers from './pages/Offers.svelte';
    import Reservations from './pages/Reservations.svelte';
    import Wishlist from './pages/Wishlist.svelte';
    import Notifications from './pages/Notifications.svelte';
    import Profile from './pages/Profile.svelte';

    const items = [
        ['offers', Offers, 'book'],
        ['reservations', Reservations, 'cart'],
        ['wishlist', Wishlist, 'star'],
        ['add', Add, 'plus-circle'],
        ['my-offers', MyOffers, 'person-lines-fill'],
        ['notifications', Notifications, 'bell'],
        ['profile', Profile, 'person-fill'],
    ];

    let selected = 0; 
</script>

<div class="md:grid md:grid-cols-5 xl:grid-cols-8 2xl:grid-cols-10">
    <div class="fixed bottom-0 flex justify-around w-screen p-2 bg-white border-t-4 md:hidden border-primary">
        <Menu items={items} bind:selected={selected} />
    </div>
    <div class="flex-col justify-between hidden h-screen p-3 border-r-4 border-primary md:flex">
        <div class="grid gap-4">
            <span class="text-xl"><Text text="title" /></span>
            <Menu items={items} bind:selected={selected} />
        </div>
        <div>Burza ucebnic <Text text="current_year" />; <a href="https://github.com/tenmajkl/bs">Source code</a></div>
    </div>
    <div class="p-3 md:col-span-4 xl:col-span-7 2xl:col-span-9">
        <svelte:component this={items[selected][1]} bind:selected={selected} />        
    </div>
</div>
