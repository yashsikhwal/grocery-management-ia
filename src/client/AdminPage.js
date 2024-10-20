import React, {useState} from 'react';
import GroceryData from './GroceryData';
import AddGrocery from './AddGrocery';
import RemoveGrocery from './RemoveGrocery';

const AdminPage = () => {
    const [refreshData, setRefreshData] = useState(false);

    const handleAddOrRemoveGrocery = () => {
        // Toggle the state to trigger re-render
        setRefreshData(prev => !prev);
    };

    return (
    <div>
        <h1>Welcome, Admin!</h1>
        <GroceryData refresh={refreshData} />
        <AddGrocery onAdd={handleAddOrRemoveGrocery}/>
        <RemoveGrocery onRemove={handleAddOrRemoveGrocery}/>
    </div>
    );
};
export default AdminPage;