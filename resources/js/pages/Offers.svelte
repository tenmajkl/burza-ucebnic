<script>
    import Text from "../components/Text.svelte";
    import Offer from '../components/Offer.svelte';
    import { _text } from "../main";

    const params = new URLSearchParams(window.location.search);

    let subjects = null;
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
                window.history.pushState('page2', 'Title', '/?page=offers');
            })
        ;
    }

    fetch('/api/offers/init')
        .then(res => res.json())
        .then(data => {
            subjects = [{id: -1, name: _text('all-subjects')}, ...data.subjects];
            states = data.states;
            sorting = data.sorts;
            if (subjects.length != 0) {
                subject = 
                    params.has('subject') 
                    ? (params.get("subject") - 0) 
                    : subjects[0].id; // javascript was a mistake what the actual duck is this
                sort = sorting[0];
                getOffers();
            }
        })
    ;

</script>

<div class="text-2xl font-bold">
    <Text text="offers-title" />
</div>
{#if subjects === null} 
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-clockwise animate-spin" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
      <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
    </svg>
{:else if subjects.length != 0}
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
            {#if (offerState == 1 || offer.reservations == 0) && (less_than === null || less_than === undefined || offer.price <= less_than)}
                <Offer {offer} bind:with_reserved={offerState} />
            {/if}
        {/each}
    </div>
{:else}
    <Text text="no-offers"></Text>
{/if}
