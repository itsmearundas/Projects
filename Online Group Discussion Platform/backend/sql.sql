CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create `users` table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Unique user ID
    name VARCHAR(50) NOT NULL,         -- Name of the user
    email VARCHAR(50) NOT NULL UNIQUE, -- Email (must be unique)
    password VARCHAR(50) NOT NULL,     -- Password
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of user registration
);

-- Create `groups` table
CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Unique group record ID
    groupid INT NOT NULL UNIQUE,            -- Unique group ID
    groupname VARCHAR(50) NOT NULL,         -- Name of the group
    number_of_members INT NOT NULL,         -- Number of members in the group
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of group creation
);

-- Create `group_members` table
CREATE TABLE group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique record ID
    groupid INT NOT NULL,               -- Group ID
    userid INT NOT NULL,                -- User ID
    FOREIGN KEY (groupid) REFERENCES groups(groupid) ON DELETE CASCADE, -- Link to groups table
    FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE        -- Link to users table
);

CREATE TABLE group_messages (

    id INT AUTO_INCREMENT PRIMARY KEY,          -- Unique message ID
    groupid INT NOT NULL,                       -- Group ID
    userid INT NOT NULL,                        -- User ID who sent the message
    message TEXT NOT NULL,                      -- The message content
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of the message
    FOREIGN KEY (groupid) REFERENCES groups(groupid) ON DELETE CASCADE,
    FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE
);
