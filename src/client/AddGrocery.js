import React, { useState } from 'react';
import axios from 'axios';

const AddGrocery = ({ onAdd }) => {
    const [itemName, setItemName] = useState('');
    const [qty, setQty] = useState(0);

    const handleAddGrocery = async (e) => {
        e.preventDefault();
        console.log(itemName);
        console.log(qty);
        try 
        {
            const response = await axios.post('http://localhost/grocery-server/insert-data.php', {
                itemName,
                qty
            });
            console.log(response.data.message);
        } 
        catch (error)
        {
            console.log('Server Error');
        }
        onAdd();
    };

    return (<div>
        <label>Item Name:</label>
            <>
            <input
                    type="text"
                    value={itemName}
                    onChange={(e) => setItemName(e.target.value)}
                    required
                />
                <label>Quantity:</label>
                <input
                    type="number" 
                    value={qty}
                    onChange={(e) => setQty(e.target.value)}
                    required
                />
                <button onClick={handleAddGrocery} type="submit">Add to database</button>
            </>
    </div>
    );
};

export default AddGrocery;