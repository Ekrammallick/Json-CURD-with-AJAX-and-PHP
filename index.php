<!DOCTYPE html>
<html>
<head>
    <title>Sonderkunden Verwaltung</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Sonderkunden Verwaltung</h1>
        
        <form id="customer-form" action="add_customer.php" method="POST">
            <div class="form-group">
                <label for="name">Name des Kunden:</label>
                <input type="text" id="name" name="name" required autocomplete="name">
            </div>

            <div class="form-group">
                <label for="order_number">Auftragsnummer:</label>
                <input type="text" id="order_number" name="order_number" required autocomplete="order-number">
            </div>

            <div class="form-group">
                <label for="delivery_date">Auslieferungsdatum:</label>
                <input type="date" id="delivery_date" name="delivery_date" required autocomplete="delivery-date">
            </div>

            <input type="submit" value="Hinzufügen">
        </form>

        <h2>Sonderkunden Liste</h2>
        <table id="customer-list">
            <tr>
                <th>Name des Kunden</th>
                <th>Auftragsnummer</th>
                <th>Auslieferungsdatum</th>
                <th></th>
            </tr>
        </table>
    </div>
    
    <script>




function loadCustomerList() {
    // Send an AJAX request to get the customer list from customer_list.php
    fetch('customer_list.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const customerList = document.getElementById('customer-list');
            customerList.innerHTML = ''; // Clear the table to display updates

            data.forEach(customer => {
                const row = customerList.insertRow();
                row.insertCell(0).textContent = customer.name;
                row.insertCell(1).textContent = customer.order_number;
                const deliveryDate = new Date(customer.delivery_date);
                const formattedDate = deliveryDate.toLocaleDateString('de-DE');
                row.insertCell(2).textContent = formattedDate;

                // Add edit and delete buttons
                const editCell = row.insertCell(3);
                const editButton = document.createElement('button');
                editButton.textContent = 'Edit';
                editButton.classList.add('edit-button');
                editCell.appendChild(editButton);

                const deleteCell = row.insertCell(4);
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.classList.add('delete-button');
                deleteCell.appendChild(deleteButton);
            });

            console.log('Customer list loaded successfully:', data);
        })
        .catch(error => {
            console.error('Error loading the customer list:', error);
        });
}

function addCustomer() {
    const name = document.getElementById('name').value;
    const order_number = document.getElementById('order_number').value;
    const delivery_date = document.getElementById('delivery_date').value;

    const newCustomer = {
        name: name,
        order_number: order_number,
        delivery_date: delivery_date
    };

    // Send an AJAX request to add the new customer to add_customer.php
    fetch('add_customer.php', {
        method: 'POST',
        body: JSON.stringify(newCustomer),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Update the customer list
        loadCustomerList();

        // Clear the form
        document.getElementById('name').value = '';
        document.getElementById('order_number').value = '';
        document.getElementById('delivery_date').value = '';
    })
    .catch(error => {
        console.error('Error adding a new customer:', error);
    });
}

function editCustomer(target) {
    // Replace the row's text with input fields for editing
    const row = target.parentNode.parentNode;
    const cells = row.cells;
    
    // Extract the customer's current data
    const name = cells[0].textContent;
    const orderNumber = cells[1].textContent;
    const deliveryDate = cells[2].textContent;
    
    // Create input fields for editing
    const nameInput = document.createElement('input');
    nameInput.value = name;
    const orderNumberInput = document.createElement('input');
    orderNumberInput.value = orderNumber;
    const deliveryDateInput = document.createElement('input');
    deliveryDateInput.value = deliveryDate;
    
    // Replace the cells with input fields
    cells[0].textContent = '';
    cells[0].appendChild(nameInput);
    cells[1].textContent = '';
    cells[1].appendChild(orderNumberInput);
    cells[2].textContent = '';
    cells[2].appendChild(deliveryDateInput);
    
    // Add a "Save" button to save changes
    const saveButton = document.createElement('button');
    saveButton.textContent = 'Save';
    saveButton.classList.add('save-button');
    cells[3].textContent = '';
    cells[3].appendChild(saveButton);
    
    // Remove the "Edit" button
    target.style.display = 'none';
}

function saveCustomer(target) {
    // Get the edited data from the input fields
    const row = target.parentNode.parentNode;
    const cells = row.cells;
    const nameInput = cells[0].querySelector('input');
    const orderNumberInput = cells[1].querySelector('input');
    const deliveryDateInput = cells[2].querySelector('input');
    
    const name = nameInput.value;
    const orderNumber = orderNumberInput.value;
    const deliveryDate = deliveryDateInput.value;
    
    // Create a customer object with the edited data
    const editedCustomer = {
        name: name,
        order_number: orderNumber,
        delivery_date: deliveryDate
    };
    console.log(editedCustomer);
   
  
    // Send an AJAX request to save the edited customer data using edit_customer.php
    fetch('edit_customer.php', {
        method: 'POST',
        body: JSON.stringify({ index: row.rowIndex, customer: editedCustomer }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
       
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        if (data.success) {
            // Update the row with the edited data
            cells[0].textContent = name;
            cells[1].textContent = orderNumber;
            cells[2].textContent = deliveryDate;
            
            // Add the "Edit" button back
            const editButton = document.createElement('button');
            editButton.textContent = 'Edit';
            editButton.classList.add('edit-button');
            cells[3].textContent = '';
            cells[3].appendChild(editButton);
        } else {
            console.error('Error saving the customer data.');
        }
    })
    .catch(error => {
        console.error('Error saving the customer data:', error);
    });
}


function deleteCustomer(target) {
    // Implement the delete functionality here.
    const row = target.parentNode.parentNode;
    const cells = row.cells;
    

    const data = {
        index: row.rowIndex
    };

    // Send an AJAX request to delete the customer using delete_customer.php
    fetch('delete_customer.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // If the delete request was successful, remove the row from the table
        if (data.success) {
            row.remove();
        }
    })
    .catch(error => {
        console.error('Error deleting a customer:', error);
    });
}


    // Once the page is loaded, retrieve and display the customer list
    loadCustomerList();
   console.log('hi')
    // Prevent form submission and add new customers
    const customerForm = document.getElementById('customer-form');
    customerForm.addEventListener('submit', function (event) {
        event.preventDefault();
        addCustomer();
    });

    // Listen for click events on the table
    const customerList = document.getElementById('customer-list');
    customerList.addEventListener('click', function (event) {
        
        const target = event.target;
        if (target.classList.contains('edit-button')) {
            console.log('edit')
            editCustomer(target);
        } else if (target.classList.contains('delete-button')) {
            console.log('delete')
            deleteCustomer(target);
        } else if (target.classList.contains('save-button')) {
            console.log('save')
            saveCustomer(target);
        }
    });

    </script>
</body>
</html>
