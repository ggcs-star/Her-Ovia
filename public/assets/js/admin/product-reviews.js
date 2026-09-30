document.addEventListener('DOMContentLoaded', function () {

    let searchTimer = null;
    let activeRequest = null;

    const ratingTitles = {
        1: 'Very Bad',
        2: 'Bad',
        3: 'Okay-Okay',
        4: 'Good',
        5: 'Very Good'
    };

    function getElement(id) {
        return document.getElementById(id);
    }

    function getFilterForm() {
        return getElement('reviewFilterForm');
    }

    function getResults() {
        return getElement('reviewResults');
    }

    function getSearchLoader() {
        return getElement('reviewSearchLoader');
    }

    function showSearchLoader() {
        const loader = getSearchLoader();

        if (loader) {
            loader.classList.remove('hidden');
        }
    }

    function hideSearchLoader() {
        const loader = getSearchLoader();

        if (loader) {
            loader.classList.add('hidden');
        }
    }

    function buildFilterUrl() {

        const form = getFilterForm();

        if (!form) {
            return null;
        }

        const url = new URL(form.action, window.location.origin);
        const formData = new FormData(form);

        url.search = '';

        formData.forEach(function (value, key) {

            if (value !== '') {
                url.searchParams.append(key, value);
            }

        });

        return url;
    }

    function updateBrowserUrl(url) {
        window.history.replaceState({}, '', url.toString());
    }

    function refreshReviews(url, updateUrl = true) {

        const results = getResults();

        if (!results || !url) {
            return;
        }

        if (activeRequest) {
            activeRequest.abort();
        }

        activeRequest = new AbortController();

        showSearchLoader();

        results.classList.add('opacity-60', 'pointer-events-none');

        fetch(url.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            signal: activeRequest.signal
        })
            .then(function (response) {

                if (!response.ok) {
                    throw new Error('Unable to load reviews.');
                }

                return response.text();

            })
            .then(function (html) {

                const parser = new DOMParser();

                const parsedDocument = parser.parseFromString(
                    html,
                    'text/html'
                );

                const newResults =
                    parsedDocument.getElementById('reviewResults');

                if (!newResults) {
                    throw new Error('Review results container not found.');
                }

                results.innerHTML = newResults.innerHTML;

                if (updateUrl) {
                    updateBrowserUrl(url);
                }

                bindReviewResultEvents();

            })
            .catch(function (error) {

                if (error.name === 'AbortError') {
                    return;
                }

                console.error(error);

            })
            .finally(function () {

                results.classList.remove(
                    'opacity-60',
                    'pointer-events-none'
                );

                hideSearchLoader();

            });
    }

    function initializeIndexPage() {

        const form = getFilterForm();

        if (!form) {
            return;
        }

        const searchInput = getElement('reviewSearch');

        form.addEventListener('submit', function (event) {

            event.preventDefault();

            const url = buildFilterUrl();

            refreshReviews(url);

        });

        form.querySelectorAll('.review-filter').forEach(function (element) {

            element.addEventListener('change', function () {

                const url = buildFilterUrl();

                refreshReviews(url);

            });

        });

        if (searchInput) {

            searchInput.addEventListener('input', function () {

                clearTimeout(searchTimer);

                searchTimer = setTimeout(function () {

                    const url = buildFilterUrl();

                    refreshReviews(url);

                }, 400);

            });

        }

        const resetButton = getElement('resetReviewFilters');

        if (resetButton) {

            resetButton.addEventListener('click', function (event) {

                event.preventDefault();

                form.reset();

                const url = new URL(
                    resetButton.href,
                    window.location.origin
                );

                refreshReviews(url);

            });

        }

    }

    function bindSelectionEvents() {

        const selectAll = getElement('selectAllReviews');

        const checkboxes =
            document.querySelectorAll('.review-checkbox');

        const bulkDeleteButton =
            getElement('bulkDeleteReviews');

        if (selectAll) {

            selectAll.onchange = function () {

                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAll.checked;
                });

                updateSelectionState();

            };

        }

        checkboxes.forEach(function (checkbox) {

            checkbox.onchange = function () {
                updateSelectionState();
            };

        });

        function updateSelectionState() {

            const selected =
                document.querySelectorAll(
                    '.review-checkbox:checked'
                );

            if (bulkDeleteButton) {

                if (selected.length > 0) {

                    bulkDeleteButton.classList.remove('hidden');

                    bulkDeleteButton.innerHTML =
                        '<svg class="mr-1 inline-block h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-9 0V5h6v2m-7 0 1 13h6l1-13"/>' +
                        '</svg>' +
                        'Delete Selected (' +
                        selected.length +
                        ')';

                } else {

                    bulkDeleteButton.classList.add('hidden');

                }

            }

            if (selectAll) {

                selectAll.checked =
                    checkboxes.length > 0 &&
                    selected.length === checkboxes.length;

                selectAll.indeterminate =
                    selected.length > 0 &&
                    selected.length < checkboxes.length;

            }

        }

        updateSelectionState();

    }

    function bindSingleDeleteEvents() {

        const deleteButtons =
            document.querySelectorAll('.delete-review');

        const deleteForm =
            getElement('singleDeleteReviewForm');

        if (!deleteForm) {
            return;
        }

        deleteButtons.forEach(function (button) {

            button.onclick = function () {

                const deleteUrl =
                    button.dataset.deleteUrl;

                if (!deleteUrl) {
                    return;
                }

                const confirmed = window.confirm(
                    'Are you sure you want to delete this review?'
                );

                if (!confirmed) {
                    return;
                }

                deleteForm.action = deleteUrl;

                deleteForm.submit();

            };

        });

    }

    function bindBulkDeleteEvents() {

        const button =
            getElement('bulkDeleteReviews');

        const form =
            getElement('bulkDeleteReviewForm');

        if (!button || !form) {
            return;
        }

        button.onclick = function () {

            const selected =
                document.querySelectorAll(
                    '.review-checkbox:checked'
                );

            if (selected.length === 0) {
                return;
            }

            const confirmed = window.confirm(
                'Are you sure you want to delete the selected ' +
                selected.length +
                ' reviews?'
            );

            if (!confirmed) {
                return;
            }

            form.querySelectorAll(
                'input[name="ids[]"]'
            ).forEach(function (input) {
                input.remove();
            });

            selected.forEach(function (checkbox) {

                const input =
                    document.createElement('input');

                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = checkbox.value;

                form.appendChild(input);

            });

            form.submit();

        };

    }

    function bindPaginationEvents() {

        const results = getResults();

        if (!results) {
            return;
        }

        results
            .querySelectorAll('.review-pagination a')
            .forEach(function (link) {

                link.onclick = function (event) {

                    event.preventDefault();

                    const url = new URL(
                        link.href,
                        window.location.origin
                    );

                    refreshReviews(url);

                    window.scrollTo({
                        top: results.offsetTop - 100,
                        behavior: 'smooth'
                    });

                };

            });

    }

    function bindReviewResultEvents() {
        bindSelectionEvents();
        bindSingleDeleteEvents();
        bindBulkDeleteEvents();
        bindPaginationEvents();
    }

    function initializeRating() {

        const ratingInputs =
            document.querySelectorAll(
                'input[name="rating"]'
            );

        if (!ratingInputs.length) {
            return;
        }

        const titleElement =
            getElement('ratingTitle');

        const clearButton =
            getElement('clearRating');

        function updateRatingTitle() {

            const selected =
                document.querySelector(
                    'input[name="rating"]:checked'
                );

            if (!titleElement) {
                return;
            }

            if (!selected) {

                titleElement.textContent = '';
                titleElement.classList.add('hidden');

                if (clearButton) {
                    clearButton.classList.add('hidden');
                }

                return;
            }

            const rating =
                Number(selected.value);

            titleElement.textContent =
                ratingTitles[rating] || '';

            titleElement.classList.remove('hidden');

            if (clearButton) {
                clearButton.classList.remove('hidden');
            }

        }

        ratingInputs.forEach(function (input) {

            input.addEventListener('change', function () {
                updateRatingTitle();
            });

        });

        if (clearButton) {

            clearButton.addEventListener('click', function () {

                ratingInputs.forEach(function (input) {
                    input.checked = false;
                });

                updateRatingTitle();

            });

        }

        updateRatingTitle();

    }

    function initializeReviewCounter() {

        const reviewText =
            getElement('reviewText') ||
            document.querySelector(
                'textarea[name="review"]'
            );

        const counter =
            getElement('reviewCounter');

        if (!reviewText || !counter) {
            return;
        }

        function updateCounter() {

            counter.textContent =
                reviewText.value.length +
                ' / 5000';

        }

        reviewText.addEventListener(
            'input',
            updateCounter
        );

        updateCounter();

    }

    function initializeImageUpload() {

        const input =
            getElement('reviewImages');

        const preview =
            getElement('imagePreview');

        if (!input || !preview) {
            return;
        }

        let selectedFiles = [];

        input.addEventListener('change', function () {

            const incomingFiles =
                Array.from(input.files || []);

            if (!incomingFiles.length) {
                return;
            }

            const validFiles = [];

            incomingFiles.forEach(function (file) {

                if (validFiles.length + selectedFiles.length >= 5) {
                    return;
                }

                const validType =
                    [
                        'image/jpeg',
                        'image/png',
                        'image/webp'
                    ].includes(file.type);

                const validSize =
                    file.size <= 5 * 1024 * 1024;

                if (validType && validSize) {
                    validFiles.push(file);
                }

            });

            selectedFiles =
                selectedFiles.concat(validFiles).slice(0, 5);

            syncImageInput();

            renderImagePreview();

        });

        function syncImageInput() {

            const dataTransfer =
                new DataTransfer();

            selectedFiles.forEach(function (file) {
                dataTransfer.items.add(file);
            });

            input.files =
                dataTransfer.files;

        }

        function renderImagePreview() {

            preview.innerHTML = '';

            selectedFiles.forEach(function (file, index) {

                const wrapper =
                    document.createElement('div');

                wrapper.className =
                    'relative aspect-square overflow-hidden rounded-xl border border-gray-100 bg-gray-50';

                const image =
                    document.createElement('img');

                image.className =
                    'h-full w-full object-cover';

                image.alt =
                    'Review image';

                const removeButton =
                    document.createElement('button');

                removeButton.type =
                    'button';

                removeButton.className =
                    'absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-black/70 text-sm font-bold text-white transition hover:bg-red-600';

                removeButton.innerHTML =
                    '&times;';

                removeButton.addEventListener(
                    'click',
                    function () {

                        selectedFiles.splice(index, 1);

                        syncImageInput();

                        renderImagePreview();

                    }
                );

                const reader =
                    new FileReader();

                reader.onload = function (event) {

                    image.src =
                        event.target.result;

                };

                reader.readAsDataURL(file);

                wrapper.appendChild(image);
                wrapper.appendChild(removeButton);

                preview.appendChild(wrapper);

            });

        }

    }

    function initializeVideoUpload() {

        const input =
            getElement('reviewVideo');

        const nameElement =
            getElement('videoName');

        if (!input || !nameElement) {
            return;
        }

        input.addEventListener('change', function () {

            const file =
                input.files && input.files[0];

            if (!file) {
                nameElement.textContent =
                    'MP4, MOV, AVI or WEBM • Maximum 20 MB';

                return;
            }

            const validTypes = [
                'video/mp4',
                'video/quicktime',
                'video/x-msvideo',
                'video/webm'
            ];

            const validSize =
                file.size <= 20 * 1024 * 1024;

            if (
                !validTypes.includes(file.type) ||
                !validSize
            ) {

                window.alert(
                    'Please select a valid video up to 20 MB.'
                );

                input.value = '';

                nameElement.textContent =
                    'MP4, MOV, AVI or WEBM • Maximum 20 MB';

                return;

            }

            nameElement.textContent =
                file.name;

        });

    }

    function initializeFormValidation() {

        const forms =
            document.querySelectorAll(
                'form[enctype="multipart/form-data"]'
            );

        forms.forEach(function (form) {

            form.addEventListener('submit', function (event) {

                const imageInput =
                    getElement('reviewImages');

                const videoInput =
                    getElement('reviewVideo');

                if (imageInput && imageInput.files.length > 5) {

                    event.preventDefault();

                    window.alert(
                        'You can upload a maximum of 5 images.'
                    );

                    return;

                }

                if (videoInput && videoInput.files.length) {

                    const video =
                        videoInput.files[0];

                    if (video.size > 20 * 1024 * 1024) {

                        event.preventDefault();

                        window.alert(
                            'Video size cannot exceed 20 MB.'
                        );

                    }

                }

            });

        });

    }

    function initializePage() {

        initializeIndexPage();
        initializeRating();
        initializeReviewCounter();
        initializeImageUpload();
        initializeVideoUpload();
        initializeFormValidation();

        bindReviewResultEvents();

    }

    initializePage();

});