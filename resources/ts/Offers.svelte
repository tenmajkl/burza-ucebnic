<script lang="ts">
    import Offer from "./components/Offer.svelte";


    export let years;
    export let subjects;

    let offers;

    async function getOffers() {
        let response = await fetch('api/offers');
        offers = await response.json()
    }

    getOffers();

</script>

{#each offers as offer}
    <Offer offer={offer} /> 
{/each}
