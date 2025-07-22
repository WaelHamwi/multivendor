import { Inertia } from '@inertiajs/react';

interface Props {
    message: string;
}

const Home = ({ message }: Props) => {
    return (
        <div>
            <h1>{message}</h1>
        </div>
    );
};

export default Home;
