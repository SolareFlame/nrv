function showOptions(option) {
    const options = document.querySelectorAll('.filter-options');
    options.forEach(optionDiv => {
        optionDiv.style.display = 'none';
    });

    const selectedOption = document.getElementById(option);
    if (selectedOption) {
        selectedOption.style.display = 'block';
    }
}