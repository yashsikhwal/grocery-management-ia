import React, { useState } from 'react';
import axios from 'axios';

import App from './App'



const NewUser = () => {

    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [message ,setMessage]   = useState('');

    const handleCreateUser = async (e) => {
        e.preventDefault();
        try 
        {
            const response = await axios.post('http://localhost/grocery-server/new-user.php', {
                username,
                password
            });
            console.log(response.data.message);
            if(response.data.message == "Success")
                setMessage("User Created");
            else if(response.data.message == "Failure")
                setMessage("User Exists");
        } 
        catch (error)
        {
            setMessage('Server Error');
        }
    };

    if(message == "User Created")
    {
        return (
            <App />
        )
    }

    return (
        <div>
            <h2>Create a New User</h2>
            <form onSubmit={handleCreateUser}>
                <div>
                    <label>Username:</label>
                    <input
                        type="text"
                        value={username}
                        onChange={(e) => setUsername(e.target.value)}
                        required
                    />
                </div>
                <div>
                    <label>Password:</label>
                    <input
                        type="text"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                    />
                </div>
                <button type="submit">Create User</button>
            </form>
            <div>
                {message}
            </div>
        </div>
    );
};

export default NewUser;
