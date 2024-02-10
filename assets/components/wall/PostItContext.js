import { createContext, useContext } from 'react';

export const PostItContext = createContext();

export const usePostItContext = () => {
  return useContext(PostItContext);
};
