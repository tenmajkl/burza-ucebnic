<script>
    import Text from "../components/Text.svelte";
    import Offer from '../components/Offer.svelte';

    const params = new URLSearchParams(window.location.search);

    let subjects = [];
    let states = {};
    let sorting = [];
    let subject;
    let state = "-1";
    let sort;
    let offerState = '0';
    let less_than = params.get('less_than');

    let offers = [];

    function getOffers() {
        fetch(`/api/offers?subject=${subject}&state=${state}&sort=${sort}&offer-state=${offerState}`, {
            method: 'get',
        })
            .then(res => res.json())
            .then(data => {
                offers = data;
            })
        ;
    }

    fetch('/api/offers/init')
        .then(res => res.json())
        .then(data => {
            subjects = data.subjects;
            states = data.states;
            sorting = data.sorts;
            subject = params.has('subject') ? (params.get("subject") - 0) : subjects[0].id; // javascript was a mistake what the actual duck is this
            sort = sorting[0];
            getOffers();
        })
    ;

    window.history.pushState('page2', 'Title', '/');
</script>

<div class="text-2xl font-bold">
    <Text text="offers-title" />
</div>
{#if subjects[0]}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class="flex flex-col">
            <label for="subject" class="text-xs text-secondary"><Text text="subject" /></label>
            <select id="subject" name="subject" class="input" bind:value={subject} on:change={getOffers}>
                {#each subjects as _subject}
                    <option value={_subject.id}>{_subject.name}</option>
                {/each}
            </select>
        </div>
        <div class="flex flex-col">
            <label for="state" class="text-xs text-secondary"><Text text="book-state" /></label>
                <select id="state" name="state" class="input" bind:value={state} on:change={getOffers}>
                {#each Object.entries(states) as [id, _state]}
                    <option value={id}><Text text="state-{_state}" /></option>
                {/each}
            </select>
        </div>
        <div class="flex flex-col">
            <label for="sort" class="text-xs text-secondary"><Text text="sorting" /></label>
            <select id="sort" name="sort" class="input" bind:value={sort} on:change={getOffers}>
                {#each sorting as _sort}
                    <option value={_sort} ><Text text="sorting-{_sort}" /></option>
                {/each}
            </select>
        </div>
        <div class="flex flex-col">
            <label for="offer-state" class="text-xs text-secondary"><Text text="offer-state" /></label>
            <select id="offer-state" name="offer-state" class="input" bind:value={offerState}>
                <option value="0"><Text text="offer-state-free" /></option>
                <option value="1"><Text text="offer-state-reserved" /></option>
            </select>
        </div>
        <div class="flex flex-col">
            <label for="max-price" class="text-xs text-secondary"><Text text="wishlist-max-price" /></label>
            <input id="max-price" name="max-price" class="input" type="number" min="0" bind:value={less_than}>
        </div>
    </div>

    <hr class="my-2 border-t-4 rounded-sm border-secondary">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2">
        {#each offers as offer}
            {#if (offerState == 1 || offer.reservations == 0) && (less_than === null || less_than === undefined || offer.price < less_than)}
                <Offer {offer} bind:with_reserved={offerState} />
            {/if}
        {/each}
    </div>
{:else}
    <Text text="no-offers"></Text>
{/if}
