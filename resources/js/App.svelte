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
    import NotificationIcon from './components/NotificationIcon.svelte';
    import Admin from './pages/Admin.svelte';
    import { getNotificationCount } from './lib';

    const items = [
        ['offers', Offers, 'book'],
        ['reservations', Reservations, 'cart'],
        ['wishlist', Wishlist, 'star'],
        ['add', Add, 'plus-circle'],
        ['my-offers', MyOffers, 'person-lines-fill'],
        ['notifications', Notifications, NotificationIcon],
        ['profile', Profile, 'person-fill'],
        admin ? ['admin', Admin, 'tools'] : [],
    ];

    let menu_data = {
        notifications: 0,
    };

    getNotificationCount()
        .then((data) => {
            menu_data.notifications = data;
        })

    let selected = 0; 
    $: window.history.pushState('page2', 'Title', '/?page='+items[selected][0]);       

    const params = new URLSearchParams(window.location.search);
    const page = params.get('page')
    if (page) {
        if (page == 'admin') {
            selected = 0;
        } else {
            let location = items.findIndex((item) => item[0] == page);
            if (location != -1) {
                selected = location;
            }
        }
    }
</script>

<div class="md:grid md:grid-cols-5 xl:grid-cols-8 2xl:grid-cols-10">
    <div class="fixed bottom-0 flex justify-around w-screen p-2 bg-white border-t-4 md:hidden border-primary shadow-2xl">
        <Menu items={items} bind:selected={selected} bind:data={menu_data} />
    </div>
    <div class="sticky overflow-auto top-0 flex-col justify-between hidden h-screen p-3 border-r-4 border-primary md:flex shadow-lg bg-white">
        <div class="grid gap-4">
            <span class="text-xl"><Text text="title" /></span>
            <Menu items={items} bind:selected={selected} bind:data={menu_data} />
        </div>
        <div>
            <div><Text text='name' /> <Text text="current_year" /></div>
            <div><a href="/about"><Text text="about-title" /></a></div>
        </div>
    </div>
    <div class="p-3 md:col-span-4 xl:col-span-7 2xl:col-span-9 pb-32 w-full overflow-auto">
        <svelte:component this={items[selected][1]} bind:selected={selected} bind:data={menu_data} />
    </div>
</div>
