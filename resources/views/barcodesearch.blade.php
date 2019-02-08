<form>
    <div class="form-group row">
        <div class="col-sm-10">
            <input name="part-barcode" type="text" class="form-control" id="part-barcode-search">
        </div>
    </div>
</form>

<script>
    document.getElementById("part-barcode-search").addEventListener("keydown", function(e) {
        if (!e) { var e = window.event; }
        e.preventDefault(); // sometimes useful

        // Enter is pressed
        if (e.keyCode == 13) {
            var barcode = document.getElementById('part-barcode-search').value
            window.location.replace("/itemlookup/sku/"+barcode);
        }
    }, false);
</script>
