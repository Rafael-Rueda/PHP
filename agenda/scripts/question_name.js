export function nameFunc(id) {
    const questionId = id.split("-")[0];
    const questionNameInput = document.getElementById(id);
    const hiddenInput = document.querySelector(`input[name="${questionId}"]`);
    const hiddenInputOldValue = hiddenInput.value;

   // Replace all ';' with '\;'
   const replacedValue = questionNameInput.value.trim().replace(/;/g, '\\;');

   // Split the old value by ';' not preceded by '\' to get an array of parts
   const parts = hiddenInputOldValue.split(/(?<!\\);/);

   // Update the first part of the array with the new question name
   parts[0] = replacedValue;

   // Join the array back into a string using ';'
   const newValue = parts.join(';');

   // Update the value of the hidden input
   hiddenInput.value = newValue;

}