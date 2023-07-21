<script>
    import Menu from './components/Menu.svelte';
    import Text from './components/Text.svelte';
    import Offers from './pages/Offers.svelte';

    let items = [
        ['offers', Offers, 'book'],
        ['reservations', null, 'cart'],
        ['wishlist', null, 'star'],
        ['add', null, 'plus-circle'],
        ['my-offers', null, 'person-lines-fill'],
        ['profile', null, 'person-fill'],
    ];

    let selected = 0;
</script>

<div class="md:grid md:grid-cols-5 xl:grid-cols-8 2xl:grid-cols-10">
    <div class="fixed bottom-0 flex justify-around w-screen p-2 border-t-4 md:hidden border-primary">
        <Menu items={items} selected={selected} />
    </div>
    <div class="flex-col justify-between hidden h-screen p-3 border-r-4 border-primary md:flex">
        <div class="grid gap-4">
            <span class="text-xl"><Text text="title" /></span>
            <Menu items={items} selected={selected} />
        </div>
    </div>
    <div class="p-3 md:col-span-4 xl:grid-cols-7 2xl:grid-cols-9">
        <svelte:component this={items[selected][1]} />        
    </div>
</div>
