<script>
    // todo upravy cecky zpravy pindik
    import Text from "../components/Text.svelte";
</script>

<div class="text-2xl font-bold">
    <Text text="my-offers-title" />
</div>

<hr class="my-2 border-t-4 rounded-sm border-secondary">

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-2">
    <div class="flex card gap-5">
        <img src="https://cdn.aukro.cz/images/sk1630929533050/programovaci-jazyk-c-106861907.jpeg" alt="cecko" class="card-image">
        <div class="flex flex-col justify-between font-bold">
            <div>
                <div class="text-xl">Programovaci jazyk C</div>
                <div class="text-sm text-secondary">0 zajemcu</div>
            </div>
            <div>
                <div class="text-sm text-secondary">NOVA</div>
                <div class="flex gap-3">
                    <div class="text-xl">200 Kc</div>
                    <i class="text-lg bi bi-chat text-blue"></i>
                    <i class="text-lg bi bi-pen text-blue"></i>
                </div>
            </div>
        </div>
    </div>
</div>
