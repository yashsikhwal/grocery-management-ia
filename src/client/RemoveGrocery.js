import React, { useState } from 'react';
import axios from 'axios';

const RemoveGrocery = ({ onRemove }) => {
    const [itemName, setItemName] = useState('');
    const [qty, setQty] = useState(0);

    const handleRemoveGrocery = async (e) => {
        e.preventDefault();
        console.log(itemName);
        console.log(qty);
        try 
        {
            const response = await axios.post('http://localhost/grocery-server/remove-data.php', 
            {
                itemName,
                qty
            });
            console.log(response.data.message);
        } 
        catch (error)
        {
            console.log('Server Error');
        }
        onRemove()
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
                <button onClick={handleRemoveGrocery} type="submit">Remove from database</button>
            </>
    </div>
    );
};

export default RemoveGrocery;