<script>
document.addEventListener('DOMContentLoaded', function () {

    const categories = @json($categories);
    const main = document.getElementById('mainCategory');
    const sub = document.getElementById('subCategory');
    const subSub = document.getElementById('subSubCategory');
    const finalCat = document.getElementById('finalCategoryId');

    if (!main || !sub || !subSub || !finalCat) return;

    const savedId = finalCat.value;

    function getCategoryPath(catId) {
        let path = [];
        let current = categories.find(c => c.id == catId);
        while (current) {
            path.unshift(current.id);
            current = categories.find(c => c.id == current.parent_id);
        }
        return path;
    }

    function loadSubs(parentId, selectedSubId = null) {
    // ✅ PEHLE PURA CLEAR KARO
    sub.innerHTML = '<option value="">Select sub category</option>';
    
    const subs = categories.filter(c => c.parent_id == parentId);

    if (subs.length === 0) {
        sub.disabled = true;
        subSub.innerHTML = '<option value="">Select sub-sub category</option>';
        subSub.disabled = true;
        return;
    }

    let html = '<option value="">Select sub category</option>';
    subs.forEach(c => {
        const sel = selectedSubId == c.id ? 'selected' : '';
        html += `<option value="${c.id}" ${sel}>${c.name}</option>`;
    });

    sub.innerHTML = html;
    sub.disabled = false;
    subSub.innerHTML = '<option value="">Select sub-sub category</option>';
    subSub.disabled = true;

    if (selectedSubId) {
        loadSubSubs(selectedSubId);
    }
}

    function loadSubSubs(parentId, selectedSubSubId = null) {
        let html = '<option value="">Select sub-sub category</option>';
        const subs = categories.filter(c => c.parent_id == parentId);

        if (subs.length === 0) {
            subSub.innerHTML = html;
            subSub.disabled = true;
            return;
        }

        subs.forEach(c => {
            const sel = selectedSubSubId == c.id ? 'selected' : '';
            html += `<option value="${c.id}" ${sel}>${c.name}</option>`;
        });

        subSub.innerHTML = html;
        subSub.disabled = false;
    }

    // EDIT MODE: Pre-select based on saved category ID
    if (savedId) {
        const selected = categories.find(c => c.id == savedId);
        if (selected) {
            const path = getCategoryPath(savedId);
            // path = [main_id, sub_id, sub_sub_id] or [main_id, sub_id] or [main_id]

            if (path.length === 3) {
                // Sub-subcategory selected
                main.value = path[0];
                loadSubs(path[0], path[1]);
                setTimeout(() => {
                    loadSubSubs(path[1], path[2]);
                }, 100);
            } else if (path.length === 2) {
                // Subcategory selected
                main.value = path[0];
                loadSubs(path[0], path[1]);
            } else if (path.length === 1) {
                // Main category selected
                main.value = path[0];
                sub.disabled = true;
                subSub.disabled = true;
            }
        }
    }

    // MAIN CATEGORY CHANGE
    main.addEventListener('change', function () {
        const mainId = this.value;
        finalCat.value = mainId;

        if (!mainId) {
            sub.innerHTML = '<option value="">Select sub category</option>';
            sub.disabled = true;
            subSub.innerHTML = '<option value="">Select sub-sub category</option>';
            subSub.disabled = true;
            finalCat.value = '';
            return;
        }

        loadSubs(mainId);
    });

    // SUB CATEGORY CHANGE
    sub.addEventListener('change', function () {
        const subId = this.value;
        finalCat.value = subId || main.value;

        if (!subId) {
            subSub.innerHTML = '<option value="">Select sub-sub category</option>';
            subSub.disabled = true;
            return;
        }

        loadSubSubs(subId);
    });

    // SUB-SUB CATEGORY CHANGE
    subSub.addEventListener('change', function () {
        finalCat.value = this.value || sub.value || main.value;
    });

});
</script>