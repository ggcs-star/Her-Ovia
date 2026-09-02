document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.category-row').forEach(function(row) {
        row.addEventListener('click', function() {
            var id = this.dataset.id;
            var icon = this.querySelector('.toggle-icon');

            if (icon) {
                var isExpanded = icon.textContent === '▼';
                toggleAllChildren(id, !isExpanded);
                icon.textContent = isExpanded ? '▶' : '▼';
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('categorySearch');
    const clearBtn = document.getElementById('clearSearch');
    const form = document.getElementById('filterForm');

    if (searchInput && clearBtn && form) {
        let debounceTimer;

        const toggleClearIcon = () => {
            clearBtn.style.display = searchInput.value ? 'block' : 'none';
        };

        searchInput.addEventListener('input', () => {
            toggleClearIcon();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => form.submit(), 400);
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            toggleClearIcon();
            form.submit();
        });

        toggleClearIcon();
    }
    if (form) {
    form.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => {
            form.submit();
        });
    });
}

    const modal = document.getElementById('imagePreviewModal');
    const previewImg = document.getElementById('previewImage');

    if (modal && previewImg) {
        document.querySelectorAll('.category-image').forEach(img => {
            img.addEventListener('click', (e) => {
                e.stopPropagation();
                previewImg.src = img.dataset.full;
                modal.classList.add('show');
            });
        });

        modal.addEventListener('click', () => {
            modal.classList.remove('show');
            previewImg.src = '';
        });
    }

});
document.addEventListener('DOMContentLoaded', () => {
    const openBtn = document.getElementById('openFilterSidebar');
    const closeBtn = document.getElementById('closeFilterSidebar');
    const sidebar = document.getElementById('filterSidebar');

    if (openBtn && sidebar) {
        openBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
        });
    }

    if (closeBtn && sidebar) {
        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });
    }
});
document.addEventListener('DOMContentLoaded', () => {
    const applyBtn = document.getElementById('applyAdvancedFilter');
    const form = document.getElementById('filterForm');

    if (applyBtn && form) {
        applyBtn.addEventListener('click', () => {
            const field = document.getElementById('advField')?.value;
            const condition = document.getElementById('advCondition')?.value;
            const value = document.getElementById('advValue')?.value;

            if (!field || !value) return;

            let fieldInput = form.querySelector('input[name="adv_field"]');
            let conditionInput = form.querySelector('input[name="adv_condition"]');
            let valueInput = form.querySelector('input[name="adv_value"]');

            if (!fieldInput) {
                fieldInput = document.createElement('input');
                fieldInput.type = 'hidden';
                fieldInput.name = 'adv_field';
                form.appendChild(fieldInput);
            }

            if (!conditionInput) {
                conditionInput = document.createElement('input');
                conditionInput.type = 'hidden';
                conditionInput.name = 'adv_condition';
                form.appendChild(conditionInput);
            }

            if (!valueInput) {
                valueInput = document.createElement('input');
                valueInput.type = 'hidden';
                valueInput.name = 'adv_value';
                form.appendChild(valueInput);
            }

            fieldInput.value = field;
            conditionInput.value = condition;
            valueInput.value = value;

            form.submit();
        });
    }
});
function toggleAllChildren(parentId, show) {
    var children = document.querySelectorAll('.parent-' + parentId);
    children.forEach(function(child) {
        if (show) {
            child.classList.remove('d-none');
        } else {
            child.classList.add('d-none');
            var childId = child.dataset.id;
            if (childId) {
                toggleAllChildren(childId, false);
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-icon').forEach(function(icon) {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();

            var row = this.closest('.category-row');
            var id = row.dataset.id;
            var isExpanded = this.textContent === '▼';

            toggleAllChildren(id, !isExpanded);
            this.textContent = isExpanded ? '▶' : '▼';
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.addEventListener('click', e => {
            e.stopPropagation();
        });

        cb.addEventListener('mousedown', e => {
            e.stopPropagation();
        });
    });
});

// आपके JavaScript फाइल में
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const form = document.getElementById('bulkDeleteForm');

    // 1️⃣ Select All toggle
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(cb => {
                cb.checked = selectAll.checked;
            });
        });
    }

    // 2️⃣ Prevent submit if nothing selected
    if (form) {
        form.addEventListener('submit', function (e) {
            const checked = document.querySelectorAll('.row-checkbox:checked');

            if (checked.length === 0) {
                e.preventDefault();
                alert('Please select at least one category to delete.');
                return false;
            }
            
            // _method field को हटाएं अगर है तो
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) {
                methodField.remove();
            }
            
            return confirm(`Are you sure you want to delete ${checked.length} category(ies)?`);
        });
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const form = document.getElementById('bulkDeleteForm');

    // 1️⃣ Select All toggle
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(cb => {
                cb.checked = selectAll.checked;
            });
        });
    }

    // 2️⃣ Prevent submit if nothing selected
    if (form) {
        form.addEventListener('submit', function (e) {
            const checked = document.querySelectorAll('.row-checkbox:checked');

            if (checked.length === 0) {
                e.preventDefault();
                alert('Please select at least one category to delete.');
                return false;
            }

            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) {
                methodField.remove();
            }

            return confirm(`Are you sure you want to delete ${checked.length} category(ies)?`);
        });
    }

    // ✅ CLEAR ADVANCED FILTER (Categories page)
    const clearAdvancedBtn = document.getElementById('clearAdvancedFilter');

    if (clearAdvancedBtn) {
        clearAdvancedBtn.addEventListener('click', () => {

            // inputs reset
            document.getElementById('advField').selectedIndex = 0;
            document.getElementById('advCondition').selectedIndex = 0;
            document.getElementById('advValue').value = '';

            // URL se advanced params hatao
            const params = new URLSearchParams(window.location.search);
            params.delete('adv_field');
            params.delete('adv_condition');
            params.delete('adv_value');

            // reload page
            window.location = `?${params.toString()}`;
        });
    }
});

// ============================================
// CATEGORY CREATE/EDIT PAGE - HIERARCHY DROPDOWNS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const mainCategory = document.getElementById('mainCategory');
    const subCategory = document.getElementById('subCategory');
    const categoryPath = document.getElementById('categoryPath');

    // Agar ye elements page pe nahi hain toh kuch mat karo
    if (!mainCategory || !subCategory) {
        return;
    }

    function getMainCategoryName(id) {
        const option = mainCategory.querySelector('option[value="' + id + '"]');
        return option ? option.textContent : '';
    }

    function getSubcategoryName(id) {
        const option = subCategory.querySelector('option[value="' + id + '"]');
        return option ? option.textContent : '';
    }

    function updatePath() {
        const mainId = mainCategory.value;
        const subId = subCategory.value;
        let path = '';

        if (mainId) {
            path = getMainCategoryName(mainId);
            if (subId) {
                path += ' → ' + getSubcategoryName(subId);
            }
        }

        if (categoryPath) {
            categoryPath.textContent = path ? '📍 ' + path : '';
        }
    }

    mainCategory.addEventListener('change', function() {
        const categoryId = this.value;

        subCategory.innerHTML = '<option value="">None</option>';

        if (categoryId) {
            fetch('/admin/get-subcategories?category_id=' + categoryId)
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    data.forEach(function(category) {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        subCategory.appendChild(option);
                    });
                })
                .catch(function(error) {
                    console.error('Error loading subcategories:', error);
                });
        }

        updatePath();
    });

    subCategory.addEventListener('change', updatePath);

    updatePath();
});