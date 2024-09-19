<style>

    .refresh-button {
        margin-top: 10px;
        margin-bottom: 5px;
    }

    @media (max-width: 576px) {
        .refresh-button {
            margin-top: 10px;
        }
    }
</style>
<button class="refresh-button btn btn-danger" onclick="refreshPage()">Rafraîchir la page</button>

<script>

    function refreshPage() {
        window.location.reload();
    }


</script>