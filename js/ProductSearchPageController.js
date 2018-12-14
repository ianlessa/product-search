var ProductSearchPageController = function() {
    this.form = null;

    this.init();
};

ProductSearchPageController.prototype.init = function() {
    this.form = this.loadForm();

    this.setEventHandlers(this.form);

    this.resetForm(this.form);
    this.updateForm(this.form);
    this.doSearch(this.form);
};

ProductSearchPageController.prototype.setEventHandlers = function(form)
{
    var _controller = this;

    form.searchButton.click(
        function(){
            _controller.doSearch(form);
        }
    );

    form.form.find(".form-control").change(
        function() {
            _controller.updateForm(form);
        }
    );

    form.resetButton.click(
        function(){
            _controller.resetForm(form);
        }
    );

    form.pageLinkPrevious.click(
        function(){
            var previousPage = form.currentPage - 1;
            if (previousPage >= 1) {
                _controller.doPagination(form, previousPage);
            }
        }
    );

    form.pageLinkNext.click(
        function()
        {
            var nextPage = form.currentPage + 1;
            if (nextPage <= form.maxPages) {
                _controller.doPagination(form, nextPage);
            }
        }
    );
};

ProductSearchPageController.prototype.resetForm = function(form)
{
    form.productName.val("");
    form.filterBy.val("");
    form.filterByTerm.val("");
    form.sort.val("");
    form.sortBy.val("");
    form.perPage.val(5);

    this.updateForm(form);
};

ProductSearchPageController.prototype.loadForm = function()
{
    var form = {};

    form.form = $("#search-form");
    form.productName = $("#product-name");
    form.filterBy = $("#filter-by");
    form.filterByTerm = $("#filter-by-term");
    form.sort = $("#sort");
    form.sortBy = $("#sort-by");
    form.perPage = $("#per-page");
    form.searchButton = $("#search-button");
    form.resetButton = $("#reset-button");
    form.queryString = $("#query-string-example");
    form.jsonResult = $("#json-result");
    form.resultCount = $("#result-count");
    form.pageLinkPrevious = $("#page-link-previous");
    form.pageLinkNext = $("#page-link-next");
    form.resultTableBody = $("#result-table-body");
    form.lastQuery = "";
    form.currentPage = 0;
    form.maxPages = 0;
    form.loadModal = $("#load-modal");

    return form;
};

ProductSearchPageController.prototype.getFormData = function(form)
{
    var formData = {};

    formData.productName = form.productName.val();
    formData.filter = {
        type: form.filterBy.val(),
        value: form.filterByTerm.val()
    };
    formData.sort = {
        type : form.sort.val(),
        value: form.sortBy.val()
    };
    formData.perPage = form.perPage.val();

    return formData;
};

ProductSearchPageController.prototype.buildQuery = function(formData)
{
    var query = "v1/products?";
    if (formData.productName !== "") {
        query += "q=" + formData.productName + "&";
    }

    if (formData.filter.type !== "" && formData.filter.value !== "") {
        query += "filter=" + formData.filter.type + ":" + formData.filter.value + "&"
    }

    if (formData.sort.type !== "" && formData.sort.value !== "") {
        query += "sort=" + formData.sort.type + ":" + formData.sort.value + "&"
    }

    query += "per_page=" + formData.perPage;

    return query;

};

ProductSearchPageController.prototype.updateForm = function(form)
{
    var formData = this.getFormData(form);

    form.filterByTerm.attr("disabled","disabled");
    if (formData.filter.type !== "") {
        form.filterByTerm.removeAttr("disabled");
    }

    form.sortBy.attr("disabled","disabled");
    if (formData.sort.type !== "") {
        form.sortBy.removeAttr("disabled");
    }

    form.queryString.val(this.buildQuery(formData))
};

ProductSearchPageController.prototype.queryApi = function (form, url) {
    var _controller = this;

    form.queryString.val("/" + url);

    form.loadModal.show();
    $.ajax({
        url
    }).done(function(data){

        form.loadModal.hide();

        var results = "";
        try {
            results = JSON.parse(data);
        }catch(e) {}

        _controller.updateProductTable(form, results, data);
    });
};

ProductSearchPageController.prototype.doSearch = function(form)
{
    var url = this.buildQuery(this.getFormData(form));
    form.lastQuery = url;
    this.queryApi(form, url);
};

ProductSearchPageController.prototype.doPagination = function(form, page) {
    var url = form.lastQuery;
    url += "&start_page=" + (page - 1);
    this.queryApi(form, url);
};

ProductSearchPageController.prototype.updateProductTable = function(form, results, data)
{
    if (results === "") {
        return;
    }

    form.jsonResult.html(data);
    form.resultCount.html(results.maxRows + "&nbsp;");
    this.updatePagination(form, results);
    this.updateTableBody(form, results);

};

ProductSearchPageController.prototype.updatePagination = function(form, results)
{
    var _controller = this;
    var maxPages = Math.ceil(results.maxRows / results.search.pagination.perPage);

    form.maxPages = maxPages;

    $(".page-number-link").unbind("click");
    $(".page-number-link").remove();

    for (var page = 1; page <= maxPages; page++) {
        var currentPageLinkHtml = $("#nav-page-link-template").html();
        var active = "";
        if (page -1 == results.search.pagination.start) {
            form.currentPage = page;
            active = "active disabled"
        }
        currentPageLinkHtml = currentPageLinkHtml.replace("@number@", page);
        currentPageLinkHtml = currentPageLinkHtml.replace("@active@", active);

        form.pageLinkNext.before($(currentPageLinkHtml));
    }

    form.pageLinkPrevious.addClass("disabled");
    form.pageLinkNext.addClass("disabled");

    if (form.currentPage != form.maxPages) {
        form.pageLinkNext.removeClass("disabled");
    }

    if (form.currentPage != 1) {
        form.pageLinkPrevious.removeClass("disabled");
    }

    $(".page-number-link").click(function(event){
        event.preventDefault();

        var link = $(event.currentTarget);
        var page = link.find("a").html();

        _controller.doPagination(form, page);
    });
};

ProductSearchPageController.prototype.updateTableBody = function(form, results)
{
    var resultData = results.results;
    var rowTemplate = $("#result-table-body-row-template").html();

    form.resultTableBody.html("");

    resultData.forEach(function(data, index){
        var numberOffset = results.search.pagination.start * results.search.pagination.perPage;
        var newRow = rowTemplate;

        newRow = newRow.replace("@number@", index + 1 +  numberOffset);
        newRow = newRow.replace("@id@", data.id);
        newRow = newRow.replace("@name@", data.name);
        newRow = newRow.replace("@brand@", data.brand);
        newRow = newRow.replace("@description@", data.description);
        newRow = $(newRow);

        form.resultTableBody.append(newRow);
    });
};
