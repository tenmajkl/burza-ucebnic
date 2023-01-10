<script>
    import Offer from "./components/Offer.svelte";

    let subject = 1;
    let sort = 0;
    
    let offers = [];

    let error = false;
  
    async function getOffers() {
        let response = await fetch(`/api/offers?subject=${subject}&year=${year}&sort=${sort}`);
        if (response.status == 400) {
            error = true;
            return;
        }

        let data = await response.json();

        if (data.length == 0) {
            error = true;
            return;
        }

        offers = data;
    }

    getOffers();
</script>

<select id="years" name="years" value={year} on:change="{getOffers}" class="card">
    {#each years as value}    
        <option value="{value.id}">{value.name}</option>
    {/each}
</select>

<select id="subjects" name="subjects" value={subject} on:change="{getOffers}" class="card">
    {#each subjects as value}    
        <option value="{value.id}">{value.name}</option>
    {/each}
</select>

<select id="sorts" name="sorts" value={sort} on:change="{getOffers}" class="card">
    {#each sorts as value}    
        <option value="{value.id}">{value.name}</option>
    {/each}
</select>

{#if error }
    {error_message}
{:else}
    {#each offers as offer}
        <Offer offer={offer} /> 
    {/each}
{/if}

