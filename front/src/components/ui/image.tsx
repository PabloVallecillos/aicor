import React from 'react';
import clsx from 'clsx';

interface ImageProps extends React.ImgHTMLAttributes<HTMLImageElement> {
  className?: string;
}

const Image: React.FC<ImageProps> = ({ className, alt, ...props }) => {
  return (
    <img
      className={clsx('object-cover', className)}
      alt={alt || 'image'}
      {...props}
    />
  );
};

export default Image;
