import React, {useState} from 'react';
import GroceryData from './GroceryData';
import RemoveGrocery from './RemoveGrocery';

const UserPage = () => { 
        const handleRemoveGrocery = () => {
            // Toggle the state to trigger re-render
            setRefreshData(prev => !prev);
        };   
        const [refreshData, setRefreshData] = useState(false);
        
        return(<div>
            <h1>Welcome, User!</h1>
            <GroceryData refresh={refreshData}/>
            <RemoveGrocery onRemove={handleRemoveGrocery}/>
        </div>);
    }

export default UserPage;